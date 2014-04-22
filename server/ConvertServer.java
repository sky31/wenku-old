import java.io.File;
import java.io.FileNotFoundException;
import org.artofsolving.jodconverter.OfficeDocumentConverter;
import org.artofsolving.jodconverter.office.DefaultOfficeManagerConfiguration;
import org.artofsolving.jodconverter.office.OfficeManager;

public class ConvertServer {
		private static  OfficeManager officeManager;
		private static String OFFICE_HOME = "C:\\Program Files (x86)\\OpenOffice.org 3";
		private static int port[] = {8100};
		
		/**
		 * 关键转换函数
		 */
		public void convert2PDF(String inputFile, String pdfFile) {
			if(inputFile.endsWith(".txt")){
				String odtFile = FileUtils.getFilePrefix(inputFile)+".odt";
				if(new File(odtFile).exists()){
					System.out.println("odt文件已存在！");
					inputFile = odtFile;
				}else{
					try {
						FileUtils.copyFile(inputFile,odtFile);
						inputFile = odtFile;
					} catch (FileNotFoundException e) {
						System.out.println("文档不存在！");
						e.printStackTrace();
					}
				}
			}
			startService();
			System.out.println("进行文档转换转换:" + inputFile + " --> " + pdfFile);
			OfficeDocumentConverter converter = new OfficeDocumentConverter(officeManager);
			converter.convert(new File(inputFile),new File(pdfFile));
			stopService();
			System.out.println();
        }
		
		public void convert2PDF(String inputFile) {
			String pdfFile = FileUtils.getFilePrefix(inputFile)+".pdf";
			convert2PDF(inputFile, pdfFile); 
		}
		
		public static void startService(){
			DefaultOfficeManagerConfiguration configuration = new DefaultOfficeManagerConfiguration();
			try {
			  System.out.println("准备启动服务....");
				configuration.setOfficeHome(OFFICE_HOME);//设置OpenOffice.org安装目录
				configuration.setPortNumbers(port); //设置转换端口，默认为8100
				configuration.setTaskExecutionTimeout(1000 * 60 * 5L);//设置任务执行超时为5分钟
				configuration.setTaskQueueTimeout(1000 * 60 * 60 * 24L);//设置任务队列超时为24小时
			 
				officeManager = configuration.buildOfficeManager();
				officeManager.start();    //启动服务
				System.out.println("office转换服务启动成功!");
			} catch (Exception ce) {
				System.out.println("office转换服务启动失败!详细信息:" + ce);
			}
		}
    
		public static void stopService(){
			System.out.println("关闭office转换服务....");
			if (officeManager != null) {
				officeManager.stop();
			}
			System.out.println("关闭office转换成功!");
		}
		
		public static void main(String []arvg) {
			String inputFile = arvg[0];
			ConvertServer cs = new ConvertServer();
			cs.convert2PDF(inputFile);
		}
}


/***
javac -encoding "utf-8" -classpath "%CLASSPATH%;./lib/commons-cli-1.1.jar;./lib/commons-io-1.4.jar;./lib/jodconverter-core-3.0-beta-4.jar;./lib/json-20090211.jar;./lib/juh-3.2.1.jar;./lib/jurt-3.2.1.jar;./lib/ridl-3.2.1.jar;./lib/unoil-3.2.1.jar" ConvertServer.java

java -classpath "%CLASSPATH%;./lib/commons-cli-1.1.jar;./lib/commons-io-1.4.jar;./lib/jodconverter-core-3.0-beta-4.jar;./lib/json-20090211.jar;./lib/juh-3.2.1.jar;./lib/jurt-3.2.1.jar;./lib/ridl-3.2.1.jar;./lib/unoil-3.2.1.jar" ConvertServer
***/