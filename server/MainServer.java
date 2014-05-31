import redis.clients.jedis.*;
import com.mongodb.*;
import com.mongodb.gridfs.*;
import org.bson.types.ObjectId;
import java.net.UnknownHostException;
import org.apache.log4j.Logger;
import java.io.*;
import java.util.Properties;
import org.artofsolving.jodconverter.office.OfficeException;

public class MainServer {
		private ConvertServer convertServer;
		private Jedis jedis;
		private MongoClient mongo;
		private DB xtudocDB;
		private GridFS xtudocGrid;
		private GridFS swfGrid;

		//日志记录
		public static Logger logger;
		
		// 一些配置常量
		private String TMP_FILE_PATH   = "/tmp/";
		private String TMP_FILE_PREFIX = "DOC_TRANS_TMP_FILE_";
		private String TMP_SWFFILE_PATH = "/tmp/doc_swfs/";
		private String PDF2SWF_PATH;
		private	String OFFICE_HOME;
		private String SWF_LANG_DIR;

		
		public MainServer() 
			throws UnknownHostException{
			//根据系统名称获取
			Properties props=System.getProperties();
			if(props.getProperty("os.name").toLowerCase().startsWith("win")) {
				PDF2SWF_PATH = "/server/SWFTools/pdf2swf.exe";
				OFFICE_HOME = "C:\\Program Files (x86)\\OpenOffice.org 3";
				SWF_LANG_DIR = "/project/svn/sky31.com/doc.sky31.com/server/xpdf-chinese-simplified-windows";
			} else {
				PDF2SWF_PATH = "/usr/local/bin/pdf2swf";
				OFFICE_HOME = "/opt/openoffice.org3";
				SWF_LANG_DIR = "/sky31/www/doc.sky31.com/server/xpdf-chinese-simplified-linux";
			}
			
			//初始化转换类
			logger.info("-- 初始化转换类..");
			convertServer = new ConvertServer(OFFICE_HOME);
			
			//初始化jedis
			logger.info("-- 初始化jedis..");
			jedis = new Jedis("localhost", 6380, 1000000);
			logger.info( "---- jedis认证：" + jedis.auth("DOC.REDIS@r720"));
			
			//初始化mongo
			logger.info("-- 初始化Mongo..");
			mongo = new MongoClient("localhost", 27017);
			xtudocDB = mongo.getDB("xtudoc");
			xtudocGrid = new GridFS(xtudocDB);
			swfGrid    = new GridFS(xtudocDB, "swf");
			
		}
		/**
		 * 关闭
		 */
		public void close() {
			convertServer.close();
			mongo.close();
		}
		
		/**
		 * 开始服务循环
		 */
		public void startService(){
			Thread thread = Thread.currentThread();
			int pauseControl = 1;
			String pdfFileName = TMP_FILE_PATH + TMP_FILE_PREFIX + "PDF.pdf";
			String swfFileName = TMP_SWFFILE_PATH + "DOC_%.swf";
			String fid = null;
			try{
				while(true) {
					fid = jedis.rpop("Q.TRANS");
					if(fid!=null){				
						//处理提取结果
						logger.debug("已经提取：fid="+fid);
						GridFSDBFile file = xtudocGrid.find(new ObjectId(fid));
						String ostFileNamePrefix = FileUtils.getFilePrefix(file.getFilename());
						String ostFileNameSufix  = FileUtils.getFileSufix(file.getFilename()).toLowerCase();
						String tmpFileName = TMP_FILE_PATH + 
								TMP_FILE_PREFIX + "OST." + ostFileNameSufix;
								
						if(file!=null) {
							try{
								file.writeTo( tmpFileName );
								String cmd;
								
								if(ostFileNameSufix.equals("pdf")) {
									// 文件本身就是pdf，不需要转PDF
									cmd = PDF2SWF_PATH + " " +
											tmpFileName + " -o " +
											swfFileName + " -q -T 9 -f -t -s languagedir="+SWF_LANG_DIR;
								} else {
									// 转换PDF
									convertServer.convert2PDF(
											tmpFileName, pdfFileName);

									cmd = PDF2SWF_PATH + " " +
											pdfFileName + " -o " +
											swfFileName + " -q -T 9 -f -t -s languagedir="+SWF_LANG_DIR;
									
								}
								// 输出cmd便于查找错误
								System.out.println(cmd);
								
								Process pcs = Runtime.getRuntime().exec(cmd);
								logger.info("正在执行转换swf命令..");
								try{
									logger.info("转换完成，返回代码：" + pcs.waitFor());	
								} catch(InterruptedException e) {
									logger.error("转换SWF出错：InterruptedException"+cmd);
									continue;
								}
								
								File swfFiles[] = FileUtils.listFiles(TMP_SWFFILE_PATH);
								if(swfFiles.length>0) {
									jedis.hset("DOC."+fid, "PAGE", 
												String.valueOf(swfFiles.length)); //保存页数
									for(int i=0; i<swfFiles.length; i++) {
										GridFSInputFile tmpSwfInputFile = 
												swfGrid.createFile(swfFiles[i]);
										tmpSwfInputFile.setFilename(swfFiles[i].getName());
										tmpSwfInputFile.setContentType("application/x-shockwave-flash");
										tmpSwfInputFile.put("realfid", fid);
										tmpSwfInputFile.put("page", getStringInteger(swfFiles[i].getName()));
										tmpSwfInputFile.save();
										swfFiles[i].delete();
									}
									logger.error("SWF文件存储成功");
									jedis.lpush("Q.SUCCESS", fid);
									
								} else {
									logger.error("SWF转换失败！fid=" + fid + " -- cmd = " + cmd);
								}
								InputStream stderr = pcs.getErrorStream();
								InputStreamReader isr = new InputStreamReader(stderr);
								BufferedReader br = new BufferedReader(isr);
								String line = null;
								System.out.println("CMD ERROR_OUT:");
								while ( (line = br.readLine()) != null){
									System.out.println(line);
								}
								System.out.println("CMD STAND_OUT:");
								br = new BufferedReader( new InputStreamReader( pcs.getInputStream() ));
								while ( (line = br.readLine()) != null){
										System.out.println(line);
								}
								System.out.println("");
								
							}catch(OfficeException e){
								//虽然失败了，但是还是要表示这个文件已经处理过
								jedis.lpush("Q.SUCCESS", fid); 
								jedis.lpush("Q.ERROR.DOC", fid);
								// 将页数保存为负数，表示转换失败
								jedis.hset("DOC."+fid, "PAGE", 
												String.valueOf(-1));
								System.out.println(fid+" 转换处理失败！！！ "+e.getMessage());
								logger.error(fid + " 转换处理失败！！！ "+e.getMessage());
							}catch (IOException e){
								jedis.lpush("Q.TRANS", fid);
								throw e;
							}
						} else {
							logger.error("xtudoc, 文件未找到，fid="+fid);
						}
					} else {
						//睡眠等待
						try{
							thread.sleep(pauseControl);
							pauseControl = pauseControl % 4;
							pauseControl = pauseControl== 0 ? 1 : pauseControl;
						}catch (InterruptedException e) {
							e.printStackTrace();
						}
					}
				}
			}catch(IOException e) {
				logger.error("文件写入失败-fid="+ fid + " : " + e.getMessage());
			}
		}
		
		public static void main(String []arvg) {
			System.out.println("");
			System.out.println("");
			System.out.println("");
			//初始化日志
			logger = Logger.getLogger(MainServer.class);

			
			try{
				logger.info("初始化MainServer..");
				MainServer ms = new MainServer();
				ms.startService();
				ms.close();
			}catch(UnknownHostException e) {
				//logger.error(e.getStackTrace());
				e.printStackTrace();
			}catch(Exception e){
				//logger.error(e.getStackTrace());
				e.printStackTrace();
			}
		}
		
		/**
		 * 获取字符串中的整数
		 */
		public static int getStringInteger(String str) {
			int res = 0;
			for(int i=0;i<str.length();i++) {
				if( str.charAt(i)>='0' && str.charAt(i)<='9' ) {
					res = res*10 + (str.charAt(i) - '0');
				}
			}
			return res;
		}
}


/***

// linux :Linux windows: Windows
Properties props=System.getProperties();
System.out.println(props.getProperty("os.name").toLowerCase().startsWith("win"));



windows下：
javac -Xlint:unchecked -Xlint:deprecation -encoding "utf-8" -classpath "%CLASSPATH%;./lib;./lib/log4j-1.2.17.jar;./lib/mongo-java-driver-2.12.1.jar;./lib/commons-cli-1.1.jar;./lib/commons-io-1.4.jar;./lib/jodconverter-core-3.0-beta-4.jar;./lib/json-20090211.jar;./lib/juh-3.2.1.jar;./lib/jurt-3.2.1.jar;./lib/ridl-3.2.1.jar;./lib/unoil-3.2.1.jar" MainServer.java

java -classpath "%CLASSPATH%;./lib;./lib/log4j-1.2.17.jar;./lib/mongo-java-driver-2.12.1.jar;./lib/commons-cli-1.1.jar;./lib/commons-io-1.4.jar;./lib/jodconverter-core-3.0-beta-4.jar;./lib/json-20090211.jar;./lib/juh-3.2.1.jar;./lib/jurt-3.2.1.jar;./lib/ridl-3.2.1.jar;./lib/unoil-3.2.1.jar" MainServer


linux 下
javac  -Xlint:unchecked -Xlint:deprecation -encoding "utf-8" -classpath ".:/usr/lib/jvm/java-1.6.0-openjdk.x86_64/lib/tools.jar:./lib/log4j-1.2.17.jar:./lib:./lib/mongo-java-driver-2.12.1.jar:./lib/commons-cli-1.1.jar:./lib/commons-io-1.4.jar:./lib/jodconverter-core-3.0-beta-4.jar:./lib/json-20090211.jar:./lib/juh-3.2.1.jar:./lib/jurt-3.2.1.jar:./lib/ridl-3.2.1.jar:./lib/unoil-3.2.1.jar" MainServer.java

java -classpath ".:/usr/lib/jvm/java-1.6.0-openjdk.x86_64/lib/tools.jar:./lib/log4j-1.2.17.jar:./lib:./lib/mongo-java-driver-2.12.1.jar:./lib/commons-cli-1.1.jar:./lib/commons-io-1.4.jar:./lib/jodconverter-core-3.0-beta-4.jar:./lib/json-20090211.jar:./lib/juh-3.2.1.jar:./lib/jurt-3.2.1.jar:./lib/ridl-3.2.1.jar:./lib/unoil-3.2.1.jar" MainServer

***/
