<?php

class ControllerPaymentYeepay extends Controller {
	protected function index() {
		include 'merchantProperties.php';	
		
    $this->data['button_confirm'] = $this->language->get('button_confirm');

		$this->data['return'] = HTTPS_SERVER . 'index.php?route=checkout/success';
		
		//此判断无用，可能是为了兼容老版本的opencart
		if ($this->request->get['route'] != 'checkout/guest_step_3') {
			$this->data['cancel_return'] = HTTPS_SERVER . 'index.php?route=checkout/payment';
		} else {
			$this->data['cancel_return'] = HTTPS_SERVER . 'index.php?route=checkout/guest_step_2';
		}

		$this->load->library('encryption');

		$encryption = new Encryption($this->config->get('config_encryption'));

		$this->data['custom'] = $encryption->encrypt($this->session->data['order_id']);

		$this->load->model('checkout/order');

		$p2_Order = $this->session->data['order_id'];

		$order_info = $this->model_checkout_order->getOrder($p2_Order);

		$currency_code ='CNY';
		$item_name = $this->config->get('config_title');  //待确认mwb
		//$first_name = $order_info['payment_firstname'];
		//$last_name = $order_info['payment_lastname'];
		//$cmdno = $this->config->get('yeepay_cmdno');      // 接口类型
		//$mch_type=$this->config->get('yeepay_mch_type');  // 虚拟物品还是实际物品
		
		/* 商户密钥 */
//		$merchantKey = $this->config->get('yeepay_key');
		
		/* 商户编码 */
		$p1_MerId = $this->config->get('yeepay_merid');

		/* 卖家 */
		//$seller =  $this->config->get('yeepay_seller');

		$total = $order_info['total'];  

		$currency_value = $this->currency->getValue($currency_code);
		$amount = $total * $currency_value;
		$amount = number_format($amount,2,'.','');

		$charset =2;  //编码类型 1:gbk 2:utf-8   待确定mwb
	
	
		//$notify_url     = HTTPS_SERVER . 'catalog/controller/payment/yeepay_callback.php';
		//$return_url		= HTTPS_SERVER . 'index.php?route=checkout/success';
		$p8_Url  = HTTPS_SERVER . 'catalog/controller/payment/yeepay_callback.php';
$data=array(
//			'bargainor_id' => $chnid,
			'p1_MerId'        => $p1_MerId,
//			'seller'	    => $seller,
//			'merchantKey'   => $merchantKey,
			'p2_Order'       => $p2_Order,
			'p3_Amt'       => $amount,  //单位改为元  //*100, // 单位为分
			'p5_Pid'       => 'opencart网店',          // 商品名称
			'p6_Pcat'       => '', // 商品种类
			'p7_Pdesc'       => '', // 商品描述
			'pa_MP'       => '', // 商户扩展信息
			'pd_FrpId'       => '', // 商户扩展信息
			'store'         => $item_name,  
			'p8_Url'		=> $p8_Url,
//			'return'		=> $return_url
		);
		
		$action = $this->pay($data);
		
		$this->data['action'] = $action;
		$this->id = 'payment';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/yeepay.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/yeepay.tpl';
		} else {
			$this->template = 'default/template/payment/yeepay.tpl';
		}

		$this->render();
	}

	private function pay($data=array()) {
		$this->log_result("Yeepay :: exciting yeepay init.");
		//require_once ("yeepay_class/PayRequestHandler.class.php");
		/* 商户号 */
		$p1_MerId = $data['p1_MerId'];
		
		/* 密钥 */
//		$merchantKey = $data['merchantKey'];
		
		/* 商家订单号,长度若超过32位，取前32位。财付通只记录商家订单号，不保证唯一。 */
		$p2_Order = $data['p2_Order'];
		
		/* 商品价格（包含运费），以分为单位 */
		$p3_Amt = $data['p3_Amt'];
		
		/* 商品名称 */
		$p5_Pid = $data['p5_Pid'];  //$data['store'].", 订单号：".$data['p2_Order']; //;
		
		$p6_Pcat = $data['p6_Pcat'];
		$p7_Pdesc = $data['p7_Pdesc'];
		$pa_MP = $data['pa_MP'];
		$pd_FrpId = $data['pd_FrpId'];  //支付通道编码，即银行编码，空即为到易宝网关选择交易银行

		/* 返回处理地址 */
		$p8_Url = $data['p8_Url'];
		
		//date_default_timezone_set(PRC);
		$strDate = date("Ymd");
		$strTime = date("His");
		
		//4位随机数
		$randNum = rand(1000, 9999);
		
		//10位序列号,可以自行调整。
		$strReq = $strTime . $randNum;
		
		/* 财付通交易单号，规则为：10位商户号+8位时间（YYYYmmdd)+10位流水号 */
		//$transaction_id = $bargainor_id . $strDate . $strReq;
		
		//请求的URL
		$reqUrl = HTTPS_SERVER . 'catalog/controller/payment/banksel.php?';
		
		$reqUrl = $reqUrl. 'p1_MerId='. urlencode($p1_MerId);
		$reqUrl = $reqUrl. '&'.'p2_Order='. urlencode($p2_Order);
		$reqUrl = $reqUrl. '&'.'p3_Amt='. urlencode($p3_Amt);
		$reqUrl = $reqUrl. '&'.'p5_Pid='. urlencode(iconv("UTF-8","GB2312",$p5_Pid));
		$reqUrl = $reqUrl. '&'.'p6_Pcat='. urlencode(iconv("UTF-8","GB2312",$p6_Pcat));
		$reqUrl = $reqUrl. '&'.'p7_Pdesc='. urlencode(iconv("UTF-8","GB2312",$p7_Pdesc));
		$reqUrl = $reqUrl. '&'.'pa_MP='. urlencode(iconv("UTF-8","GB2312",$pa_MP));
		$reqUrl = $reqUrl. '&'.'p8_Url='. urlencode($p8_Url);
		$reqUrl = $reqUrl. '&'.'pd_FrpId='. urlencode($pd_FrpId);
		
		return $reqUrl;
	}


	public function callback() {
		// Order status for Opencart
		/*
		$order_status = array(
			"Canceled"        => 7,
			"Canceled_Reversal"   => 9,
			"Chargeback"     	=> 13,
			"Complete"     		=> 5,
			"Denied" 			=> 8,
			"Failed"        	=> 10 ,
			"Pending"           => 1,
			"Processing"  		 => 2,
			"Refunded"        	  => 11,
			"Reversed"  		 => 12,
			"Shipped"     	  => 3
		);
		$this->log_result("yeepay :: exciting callback function.");
		*/
		
		include 'yeepayCommon.php';	

		/* 商户密钥 */
		$merchantKey = $this->config->get('yeepay_key');
		
		/* 商户编码 */
		$p1_MerId = $this->config->get('yeepay_merid');

		//$this->func_pay($order_status);
		#	解析返回参数.
		$return = getCallBackValue($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$hmac);
		
		#	判断返回签名是否正确（True/False）
		$bRet = CheckHmac($p1_MerId,$r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$merchantKey,$hmac);
		#	以上代码和变量不需要修改.
			 	
		header("Content-type:text/html;charset=utf-8");
		#	校验码正确.
		if($bRet){
			if($r1_Code=="1"){
			#	需要比较返回的金额与商家数据库中订单的金额是否相等，只有相等的情况下才认为是交易成功.
			#	并且需要对返回的处理进行事务控制，进行记录的排它性处理，在接收到支付结果通知后，判断是否进行过业务逻辑处理，不要重复进行业务逻辑处理，防止对同一条交易重复发货的情况发生. 
			
				$this->load->model('checkout/order');
				$r6_Order = $this->request->get['r6_Order'];//$this->session->data['r6_Order'];
				$order_info = $this->model_checkout_order->getOrder($r6_Order);
				if ($order_info) {
					$order_status_id = $order_info["order_status_id"];
					// 确定订单没有重复支付
					if ($order_status_id != $this->config->get('yeepay_order_status_id')) {
						$total = $order_info['total'];  
						$currency_code ='CNY';
						$currency_value = $this->currency->getValue($currency_code);
						$amount = $total * $currency_value;
						$amount = number_format($amount,2,'.','');
					  if(0 == bccomp($amount, $r3_Amt,2)){
			 				//此处需要设置订单状态为“已付款”或“待处理”，根据具体情况定
							$this->model_checkout_order->confirm($r6_Order, $this->config->get('yeepay_order_status_id'));
							$yeepay_order_status_id = $this->config->get('yeepay_order_status_id');
							$this->model_checkout_order->update($order_info['order_id'], $yeepay_order_status_id);
						}else{
							echo "交易失败，交易金额有误";
							return;
					  }	
					}else{
						echo "fail";
					}
				}else{
					log_result("Yeepay No Order Found.");
					echo "fail";
				}
			  
				if($r9_BType=="1"){
					Header("Location: ../../../index.php?route=checkout/success");
					echo "交易成功";
					echo  "<br />在线支付页面返回";
				}elseif($r9_BType=="2"){
					#如果需要应答机制则必须回写流,以success开头,大小写不敏感.
					Header("Location: ../../../index.php?route=checkout/success");
					echo "success";
					echo "<br />交易成功";
					echo  "<br />在线支付服务器返回";      			 
				}
			}
			
		}else{
			echo "交易信息被篡改";
		}
	}
	
	private function  log_result($word) {
		/*$fp = fopen("log_tenpey.txt","alex");
		flock($fp, LOCK_EX) ;
		fwrite($fp,$word."：excute ：".strftime("%Y-%m-%d %H:%I:%S",time())."\t\n");
		flock($fp, LOCK_UN);
		fclose($fp);
		*/
	}
	
	
	
	
}

?>