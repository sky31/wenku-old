<?php
namespace Common\Util;

use Common\Model\StudentInfoModel;
class StudentUtil{
	private $fetch;
	
	/**
	 * @var StudentInfoModel
	 */
	private $student;
	
	function __construct(StudentInfoModel $student) {
		$this->fetch = new MyUrlFetch();
		$this->student = $student;		
		//$res = $this->loginToStudentPlatform();
		//if($res['ret']!=0) throw new WeixinException($res['info']);
	}
	
	private function loginToStudentPlatform() {
		$this->fetch->get("http://202.197.224.134:8083/jwgl/login.jsp");
		$fields = array(
			'username'=>$this->student->stuno, 'password'=>$this->student->stupwd,
			'identity'=>'student', 'role'=>'1');

		$this->fetch->setPostArray($fields);
		
		$response = iconv("GBK", "UTF-8//IGNORE",
				$this->fetch->post("http://202.197.224.134:8083/jwgl/logincheck.jsp"));
		$http_code = $this->fetch->getHttpCode()."";
		if($http_code[0]==2) {
			if(strpos($response, "密码错误") == FALSE) {
				$response = iconv("GBK", "UTF-8//IGNORE",
						$this->fetch->post("http://202.197.224.134:8083/jwgl/index1.jsp"));
				//echo "<textarea>".$response."</textarea>";
				preg_match('/<font color=red>(.+?)同学<\/font>/', $response, $matchs);
				//var_dump($matchs);
				///exit();
				$res['ret']  = 0;
				$res['info'] = $matchs[1];
			} else {
				$res['ret']  = 1;
				$res['info'] = "帐号或密码错误。也可能是教务管理系统挂掉了，可以再试一下T_T..";
			}
		} else {
			$res['ret']  = 1;
			$res['info'] = "无法访问学校网站。可能是学校网站挂掉了T_T..";
		}
		return $res;	
	}
	/**
	 * 从教务管理系统获取用户的姓名
	 */
	function getName() {
		$res = $this->loginToStudentPlatform(); //先登录
		return $res;
	}
	
	/**
	 * 从教务管理系统获取课表
	 * @return string|multitype:number string |Ambigous <string, multitype:multitype:unknown number  >
	 */
	public function getCourse() {
		$res = $this->loginToStudentPlatform(); //先登录
		if($res['ret']==1) return $res; //登录失败
		
		$response = iconv("GBK", "UTF-8//IGNORE",
				$this->fetch->get("http://202.197.224.134:8083/jwgl/xk/xk1_kb_gr.jsp?xq1=01"));
	
		if( !(empty($response) || preg_match("/登录超时|重新登录/", $response)) ) {
			$info=array();
			$str = str_replace("\n", "", $response);
			$str = str_replace("\r", "", $str);
			$str = str_replace(" ", "", $str);
			
			$str = str_replace("<tdvalign=top></td>", "<tdvalign=top><tablewidth=100%border=0cellpadding=0cellspacing=0></table></td>", $str);
			preg_match_all("/<tdvalign=top>(.*?)<\/table><\/td>/", $str ,$match);

			$match=$match[0];
			$mnums = count($match);
			if( $mnums!=35 )
				return array('ret'=>1, '课表解析失败');
			$info = array();
			for($index=0;$index<35;$index++) {
				$wd = $index%7;
				$cn = (int)($index/7);
				preg_match_all("/<tablewidth=100%border=0cellpadding=0cellspacing=0>(.*?)<\/table>/i", $match[$index]."</table>", $smatch);
				$smatch = $smatch[1];

				foreach( $smatch as $key=>$v ) {
					if( $v!="" ) {
						$v=str_replace("colspan=2", "", $v);
						preg_match_all("/<td>(.*?)<\/td>/i", $v, $vm);
						$vm=$vm[1];
						$info[] =array(
								'course'=>$vm[0],
								'classroom'=>$vm[1],
								'teacher'=>$vm[2],
								'duration'=>$vm[3],
								'week'=>$wd,
								'time_no'=>$cn,
						);
					}
				}
			}
			$res['ret'] = 0;
			$res['info'] = $info;
		}else {
			$res['ret'] = 1;
			$res['info'] = "登录失败";
		}
		return $res;
	}
	
	/**
	 * 从教务管理系统获取学生的成绩
	 */
	function getScore() {
		$res = $this->loginToStudentPlatform(); //先登录
		if($res['ret']==1) return $res; //登录失败
		
		$str = iconv("GB2312", "UTF-8",
				$this->fetch->get("http://202.197.224.134:8083/jwgl/cj/cj1_cjLiebiao.jsp?xq=0&xkjc=&type=null&xkdl2=&xh={$this->student->stuno}&bh=null"));
		$str = str_replace("\n", "", $str);
		$str = str_replace("\r", "", $str);
		$str = str_replace(" ", "", $str);
		$str = str_replace("&nbsp;", "", $str);
		$str = str_replace("style=\"color:red\"", "", $str);
		
		$r = preg_match_all("/<tr>(.*?)<\/tr>/u", $str, $matchs);
		$matchs = $matchs[1];
		$str = "翼宝为你查询到的成绩：\n\n";
		$count = count($matchs);
		$pre = -1;
		for($i = $count-1; $i >=0; $i--){
			preg_match_all("/<td>(.*?)<\/td>/u", $matchs[$i], $m);
			$m = $m[1];
			if( $pre!=-1 && ($pre!=intval($m[6]) || $m[6]=="" ) ) break;
			else $pre = intval($m[6]);
				
			$str.=($m[0]."【".substr($m[1],0,6)."】\n平时：".$m[3]." 考试：".$m[4]." 期评：".$m[5]."\n------------\n");
		}
		return array(
			'ret'=> 0,
			'info'=>$str
		);
	}
	
	
	/**
	 * 从本部的财务管理系统获取学费
	 * @return Array
	 */
	function getPaymentFromXtu(){
		$datas = array("TxtName"=>$this->student->stuno, "TxtPass"=>$this->student->cwpwd);
		$this->fetch->setPostArray($datas);
		$str = iconv("GB2312", "UTF-8",
				$this->fetch->post("http://cwcx.xtu.edu.cn:8004/cwcx4/sf40/"));
	
		$hc = $this->fetch->getHttpCode()."";
		//var_dump($hc);
		//exit();
		if(($hc[0] != 2 && $hc[0] != 3) || $hc == '0')
			return array('ret'=>1, 'info'=>'财务查询系统无法访问');
	
		if(strpos($str, "对象已移动")) {
			$str=iconv("GB2312", "UTF-8",
					$this->fetch->get("http://cwcx.xtu.edu.cn:8004/cwcx4/sf40/FindOk.asp"));
			$str = str_replace(" ", "", replace_wrap($str));
	
			//cho $str;
			preg_match_all("/<TRclass=\"right\">(.*?)<\/TR>/", $str, $matchs);
	
			//获取卡号：
			preg_match("/帐号：([0-9]{19})/", $str, $m2);
	
			$mnums = count($matchs[1]);
			//echo $mnums;
			$pre = NULL;
			$content = "建行卡号：".$m2[1]."\n";
			for($i = $mnums-2; $i >= 0; $i--) {
				//echo $i." ";
				preg_match_all("/<TD.*?>(.*?)<\/TD>/", $matchs[1][$i], $m);
				if($pre != NULL && $pre!=$m[1][0]) break;
				if($pre==NULL) $content.='学期：'.$m[1][0]."\n";
				$content.="--------\n【".$m[1][1]."】\n";
				$content.="应交：".$m[1][2]."\n";
				$content.="实交：".$m[1][3]."\n";
				$content.="减免：".$m[1][4]."\n";
				$content.="退费：".$m[1][5]."\n";
				$content.="欠交：".$m[1][6]."\n";
				$pre = $m[1][0];
			}
			$content .= "--------\n总学费为以上几项相加，在提示欠费时应尽快在绑定的建行卡上存入欠交的总学费。\n缴费状态随时可查。";
			return array(
				'ret'=>'0',
				'info'=>$content
			);
		} else {
			return array(
				'ret'=>2,
				'info'=>'财务管理系统密码错误'
			);
		}

	}
	
	/**
	 * 从兴湘的财务管理系统获取学费
	 * @return Array
	 */
	function getPaymentFromXxu(){
		$datas = array("TxtName"=>$this->student->stuno, "TxtPass"=>$this->student->cwpwd);
		$this->fetch->setPostArray($datas);
		$str = iconv("GB2312", "UTF-8",
				$this->fetch->post("http://cwcx.xtu.edu.cn:8004/cwcxxx/sf40/"));
	
		$hc = $this->fetch->getHttpCode()."";
		//var_dump($hc);
		//exit();
		//return array('ret'=>0, 'info'=>$hc);
		
		if(($hc[0] != 2 && $hc[0] != 3) || $hc == '0')
			return array('ret'=>1, 'info'=>'财务查询系统无法访问');
	
		if(strpos($str, "对象已移动")) {
			$str=iconv("GB2312", "UTF-8",
					$this->fetch->get("http://cwcx.xtu.edu.cn:8004/cwcxxx/sf40/FindOk.asp"));
			$str = str_replace(" ", "", replace_wrap($str));
	
			//cho $str;
			preg_match_all("/<TRclass=\"right\">(.*?)<\/TR>/", $str, $matchs);
	
			//获取卡号：
			preg_match("/帐号：([0-9]{19})/", $str, $m2);
	
			$mnums = count($matchs[1]);
			//echo $mnums;
			$pre = NULL;
			$content = "建行卡号：".$m2[1]."\n";
			$content .= "姓名：".$this->student->name."\n";
			for($i = $mnums-2; $i >= 0; $i--) {
				//echo $i." ";
				preg_match_all("/<TD.*?>(.*?)<\/TD>/", $matchs[1][$i], $m);
				if($pre != NULL && $pre!=$m[1][0]) break;
				if($pre==NULL) $content.='学期：'.$m[1][0]."\n";
				$content.="--------\n【".$m[1][1]."】\n";
				$content.="应交：".$m[1][2]."\n";
				$content.="实交：".$m[1][3]."\n";
				$content.="减免：".$m[1][4]."\n";
				$content.="退费：".$m[1][5]."\n";
				$content.="欠交：".$m[1][6]."\n";
				$pre = $m[1][0];
			}
			$content .= "--------\n总学费为以上几项相加，在提示欠费时应尽快在绑定的建行卡上存入欠交的总学费。\n缴费状态随时可查。";
			return array(
					'ret'=>'0',
					'info'=>$content
			);
		} else {
			return array(
					'ret'=>2,
					'info'=>'财务管理系统密码错误'
			);
		}
	
	}
	
	/**
	 * 获取学费
	 * @return Array
	 */
	function getPayments(){
		if(preg_match("/[0-9]{4}96/", $this->student->stuno))
			return $this->getPaymentFromXxu();
		else
			return $this->getPaymentFromXtu();
	}
	
	/**
	 * 
	 */
	function getRank() {
		$res = $this->loginToStudentPlatform(); //先登录
		if($res['ret']==1) return $res; //登录失败
		
		$year = intval(date("Y"));
		$month =  intval(date("n"));
		if( $month <7 ) $xq = ($year-1)."02";
		else $xq = $year."01";
		
		//var_dump($xq);
		//echo "http://202.197.224.134:8083/jwgl/cj/cj1_paiming.jsp?xq1=$xq&xh={$this->student->stuno}";
		
		$str = iconv("GB2312", "UTF-8",
				$this->fetch->get("http://202.197.224.134:8083/jwgl/cj/cj1_paiming.jsp?xq1=$xq&xh={$this->student->stuno}"));
		return array('ret'=>0, 'info'=>$str);
	}
}