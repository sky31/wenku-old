import java.io.File;
import java.io.IOException;

public class SWFToolsSWFConverter{
	//windows
	private static String PDF2SWF_PATH = "D:\\server\\SWFTools\\pdf2swf.exe";
	
	public void convert2SWF(String inputFile, String swfFile) {
		File pdfFile = new File(inputFile);
		File outFile = new File(swfFile);
		if(!inputFile.endsWith(".pdf")){
			System.out.println("文件格式非PDF！");
			return ;
		}
		if(!pdfFile.exists()){
			System.out.println("PDF文件不存在！");
			return ;
		}
		if(outFile.exists()){
			System.out.println("SWF文件已存在！");
			return ;
		}
		String command = PDF2SWF_PATH +" \""+inputFile+"\" -o "+swfFile+" -T 9 -f -t -s storeallcharacters";
		try {
			System.out.println("开始转换文档: "+inputFile);
			System.out.println("DEBUG command："+command);
			System.out.println("DEBUG EXEC RETURN："+Runtime.getRuntime().exec(command));
			System.out.println("成功转换为SWF文件！");
		} catch (IOException e) {
			e.printStackTrace();
			System.out.println("转换文档为swf文件失败！");
		}
		
	}
	
	public void convert2SWF(String inputFile) {
		String swfFile = FileUtils.getFilePrefix(inputFile)+".swf";
		convert2SWF(inputFile,swfFile);
	}
	
	public static void main(String []arvg) {
		String inputFile = arvg[0];
		SWFToolsSWFConverter swf = new SWFToolsSWFConverter();
		String swfFile = FileUtils.getFilePrefix(inputFile)+"%.swf";
		swf.convert2SWF(inputFile, swfFile);
	}

}
