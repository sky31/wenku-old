import redis.clients.jedis.*;
import com.mongodb.*;

public class MainServer {
		private ConvertServer convertServer;
		private Jedis jedis;
		public MainServer() {
			convertServer = new ConvertServer();
			
			Jedis jedis = new Jedis("localhost", 6380);
			jedis.auth("DOC.REDIS@r720");
		}
		
		public static void main(String []arvg) {
			 Mongo mongo = new Mongo("localhost", 27017);
			 DB db = mongo.getDB("yourdb");
		}
}


/***

// linux :Linux windows: Windows
Properties props=System.getProperties();
System.out.println(props.getProperty("os.name").toLowerCase().startsWith("win"));



windows下：
javac -Xlint:unchecked -encoding "utf-8" -classpath "%CLASSPATH%;./lib;./lib/mongo-java-driver-2.12.1.jar;./lib/commons-cli-1.1.jar;./lib/commons-io-1.4.jar;./lib/jodconverter-core-3.0-beta-4.jar;./lib/json-20090211.jar;./lib/juh-3.2.1.jar;./lib/jurt-3.2.1.jar;./lib/ridl-3.2.1.jar;./lib/unoil-3.2.1.jar" MainServer.java

java -classpath "%CLASSPATH%;./lib;./lib/mongo-java-driver-2.12.1.jar;./lib/commons-cli-1.1.jar;./lib/commons-io-1.4.jar;./lib/jodconverter-core-3.0-beta-4.jar;./lib/json-20090211.jar;./lib/juh-3.2.1.jar;./lib/jurt-3.2.1.jar;./lib/ridl-3.2.1.jar;./lib/unoil-3.2.1.jar" MainServer


linux 下
javac  -Xlint:unchecked -encoding "utf-8" -classpath ".:/usr/lib/jvm/java-1.6.0-openjdk.x86_64/lib/tools.jar:./lib:./lib/mongo-java-driver-2.12.1.jar:./lib/commons-cli-1.1.jar:./lib/commons-io-1.4.jar:./lib/jodconverter-core-3.0-beta-4.jar:./lib/json-20090211.jar:./lib/juh-3.2.1.jar:./lib/jurt-3.2.1.jar:./lib/ridl-3.2.1.jar:./lib/unoil-3.2.1.jar" MainServer.java

java -classpath ".:/usr/lib/jvm/java-1.6.0-openjdk.x86_64/lib/tools.jar:./lib:./lib/mongo-java-driver-2.12.1.jar:./lib/commons-cli-1.1.jar:./lib/commons-io-1.4.jar:./lib/jodconverter-core-3.0-beta-4.jar:./lib/json-20090211.jar:./lib/juh-3.2.1.jar:./lib/jurt-3.2.1.jar:./lib/ridl-3.2.1.jar:./lib/unoil-3.2.1.jar" MainServer b.doc

***/