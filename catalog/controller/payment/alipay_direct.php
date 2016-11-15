<?php
require_once("alipay_direct_service.php");
require_once("alipay_direct_notify.php");

/*  ��־��Ϣ,��֧���������Ĳ�����¼����*/	
function  log_result($word) {
	
	$fp = fopen("../../../log_alipay_direct_" . strftime("%Y%m%d",time()) . ".txt","a");	
	flock($fp, LOCK_EX) ;
	fwrite($fp,$word."::Date��".strftime("%Y-%m-%d %H:%I:%S",time())."\t\n");
	flock($fp, LOCK_UN); 
	fclose($fp);
	
}

class ControllerPaymentAlipayDirect extends Controller {
	protected function index() {
		// Ϊ alipay.tpl ׼������
    	$this->data['button_confirm'] = $this->language->get('button_confirm');
		$this->data['button_back'] = $this->language->get('button_back');

		// url

		$this->data['return'] = HTTPS_SERVER . 'index.php?route=checkout/success';
		
		if ($this->request->get['route'] != 'checkout/guest_step_3') {
			$this->data['cancel_return'] = HTTPS_SERVER . 'index.php?route=checkout/payment';
		} else {
			$this->data['cancel_return'] = HTTPS_SERVER . 'index.php?route=checkout/guest_step_2';
		}
		
		$this->load->library('encryption');
		
		$encryption = new Encryption($this->config->get('config_encryption'));
		
		$this->data['custom'] = $encryption->encrypt($this->session->data['order_id']);
		
		if ($this->request->get['route'] != 'checkout/guest_step_3') {
			$this->data['back'] = HTTPS_SERVER . 'index.php?route=checkout/payment';
		} else {
			$this->data['back'] = HTTPS_SERVER . 'index.php?route=checkout/guest_step_2';
		}

		// ��ȡ��������
		$this->load->model('checkout/order');

		$order_id = $this->session->data['order_id'];
		
		$order_info = $this->model_checkout_order->getOrder($order_id);

		
		/*
		$this->data['business'] = $this->config->get('alipay_direct_seller_email');
		$this->data['item_name'] = html_entity_decode($this->config->get('config_store'), ENT_QUOTES, 'UTF-8');				
		$this->data['currency_code'] = $order_info['currency'];
		$this->data['tgw'] = $this->session->data['order_id'];
		$this->data['amount'] = $this->currency->format($order_info['total'], $order_info['currency'], $order_info['value'], FALSE);
		$this->data['total'] = $order_info['total'];
		$this->data['currency'] = $order_info['currency'];
		$this->data['value'] = $order_info['value'];
		$this->data['first_name'] = html_entity_decode($order_info['payment_firstname'], ENT_QUOTES, 'UTF-8');	
		$this->data['last_name'] = html_entity_decode($order_info['payment_lastname'], ENT_QUOTES, 'UTF-8');	
		$this->data['address1'] = html_entity_decode($order_info['payment_address_1'], ENT_QUOTES, 'UTF-8');	
		$this->data['address2'] = html_entity_decode($order_info['payment_address_2'], ENT_QUOTES, 'UTF-8');	
		$this->data['city'] = html_entity_decode($order_info['payment_city'], ENT_QUOTES, 'UTF-8');	
		$this->data['zip'] = html_entity_decode($order_info['payment_postcode'], ENT_QUOTES, 'UTF-8');	
		$this->data['country'] = $order_info['payment_iso_code_2'];
		$this->data['notify_url'] = $this->url->http('payment/alipay/callback');
		$this->data['email'] = $order_info['email'];
		$this->data['invoice'] = $this->session->data['order_id'] . ' - ' . html_entity_decode($order_info['payment_firstname'], ENT_QUOTES, 'UTF-8') . ' ' . html_entity_decode($order_info['payment_lastname'], ENT_QUOTES, 'UTF-8');
		$this->data['lc'] = $this->session->data['language'];
		*/

		// �����ύ��ַ
		$seller_email = $this->config->get('alipay_direct_seller_email');		// �̼�����
		$security_code = $this->config->get('alipay_direct_security_code');	//��ȫ������
		$partner = $this->config->get('alipay_direct_partner');				//�������ID
		$currency_code = $this->config->get('alipay_direct_currency_code');				//����Ҵ��ţ�CNY��
		$item_name = $this->config->get('config_store');
		$first_name = $order_info['payment_firstname'];	
		$last_name = $order_info['payment_lastname'];

		$total = $order_info['total'];
		if($currency_code == ''){
			$currency_code = 'CNY';
		}
		
		$currency_value = $this->currency->getValue($currency_code);
		$amount = $total * $currency_value;
		$amount = number_format($amount,2,'.','');
		//$this->data['amount'] = html_entity_decode($this->config->get('config_store'), ENT_QUOTES, 'GB2312');
		

		$_input_charset = "utf-8";  //�ַ������ʽ  Ŀǰ֧�� GBK �� utf-8
		$sign_type      = "MD5";    //���ܷ�ʽ  ϵͳĬ��(��Ҫ�޸�)
		$transport      = "http";  //����ģʽ,����Ը����Լ��ķ������Ƿ�֧��ssl���ʶ�ѡ��http�Լ�https����ģʽ(ϵͳĬ��,��Ҫ�޸�)
		$notify_url     = HTTP_SERVER . 'catalog/controller/payment/alipay_direct_notify_url.php';
		$return_url		= HTTPS_SERVER . 'index.php?route=checkout/success';
		$show_url       = "";        //����վ��Ʒ��չʾ��ַ
		$out_trade_no = $order_id;
		$subject = 'Order ID ' . $order_id . ' of ' .$item_name;       //��Ʒ���ƣ�����
		$body = 'for ' . $last_name . ' ' . $first_name;       //��Ʒ����������			
		$total_fee = $amount;

		//��չ���ܲ�������Ĭ��֧����ʽ
		$paymethod    = "directPay";	//Ĭ��֧����ʽ���ĸ�ֵ��ѡ��bankPay(����); cartoon(��ͨ); directPay(���); CASH(����֧��)
		$defaultbank  = "";

		$pay_mode	  =  !empty($this->session->data['alipay_direct_method']) ? $this->session->data['alipay_direct_method'] : '';
		if ($pay_mode != '') {			
			$paymethod    = "bankPay";		//Ĭ��֧����ʽ���ĸ�ֵ��ѡ��bankPay(����); cartoon(��ͨ); directPay(���); CASH(����֧��)
			$defaultbank  = $pay_mode;		//Ĭ���������ţ������б��http://club.alipay.com/read.php?tid=8681379
		}

		//��չ���ܲ�������������
		//������ѡ���Ƿ��������㹦��
		//exter_invoke_ip��anti_phishing_keyһ�������ù�����ô���Ǿͻ��Ϊ�������
		//���������㹦�ܺ󣬷��������������Ա���֧��Զ��XML�����������úøû�����
		//��Ҫʹ�÷����㹦�ܣ����class�ļ�����alipay_function.php�ļ����ҵ����ļ����·���query_timestamp����������ע�ͶԸú��������޸�
		//����ʹ��POST��ʽ��������
		$anti_phishing_key  = '';			//������ʱ���
		$exter_invoke_ip = '';				//��ȡ�ͻ��˵�IP��ַ�����飺��д��ȡ�ͻ���IP��ַ�ĳ���
		//�磺
		//$exter_invoke_ip = '202.1.1.1';
		//$anti_phishing_key = query_timestamp($partner);		//��ȡ������ʱ�������


		//��չ���ܲ�����������
		$extra_common_param = '';			//�Զ���������ɴ���κ����ݣ���=��&�������ַ��⣩��������ʾ��ҳ����
		$buyer_email		= '';			//Ĭ�����֧�����˺�

		//��չ���ܲ�����������(��Ҫʹ�ã��밴��ע��Ҫ��ĸ�ʽ��ֵ)
		$royalty_type		= "";			//������ͣ���ֵΪ�̶�ֵ��10������Ҫ�޸�
		$royalty_parameters	= "";
		//�����Ϣ��������Ҫ����̻���վ���������̬��ȡÿ�ʽ��׵ĸ������տ��˺š��������������˵�������ֻ������10��
		//����������ܺ���С�ڵ���total_fee
		//�����Ϣ����ʽΪ���տEmail_1^���1^��ע1|�տEmail_2^���2^��ע2
		//�磺
		//royalty_type = "10"
		//royalty_parameters	= "111@126.com^0.01^����עһ|222@126.com^0.01^����ע��"

		//����Ҫ����Ĳ������飬����Ķ�
		$parameter = array(
				"service"			=> "create_direct_pay_by_user",	//�ӿ����ƣ�����Ҫ�޸�
				"payment_type"		=> "1",               			//�������ͣ�����Ҫ�޸�

				//��ȡ�����ļ�(alipay_config.php)�е�ֵ
				"partner"			=> $partner,
				"seller_email"		=> $seller_email,
				"return_url"		=> $return_url,
				"notify_url"		=> $notify_url,
				"_input_charset"	=> $_input_charset,
				"show_url"			=> $show_url,

				//�Ӷ��������ж�̬��ȡ���ı������
				"out_trade_no"		=> $out_trade_no,
				"subject"			=> $this->config->get('config_name') . ' - #' . $order_id,
				"body"				=> $this->config->get('config_name') . ' - #' . $order_id,
				"total_fee"			=> $total_fee,

				//��չ���ܲ�������������ǰ
				"paymethod"			=> $paymethod,
				"defaultbank"		=> $defaultbank,

				//��չ���ܲ�������������
				"anti_phishing_key"	=> $anti_phishing_key,
				"exter_invoke_ip"	=> $exter_invoke_ip,

				//��չ���ܲ��������Զ������
				"buyer_email"		=> $buyer_email,
				"extra_common_param"=> $extra_common_param,
				
				//��չ���ܲ�����������
				"royalty_type"		=> $royalty_type,
				"royalty_parameters"=> $royalty_parameters
		);
		/*
		$parameter = array(
			"service"        => "create_partner_trade_by_buyer",  //��������
			"partner"        => $partner,         //�����̻���
			"return_url"     => $return_url,      //ͬ������
			"notify_url"     => $notify_url,      //�첽����
			"_input_charset" => $_input_charset,  //�ַ�����Ĭ��ΪGBK
			"subject"        => 'Order ID ' . $order_id . ' of ' .$item_name,       //��Ʒ���ƣ�����
			"body"           => 'for ' . $last_name . ' ' . $first_name,       //��Ʒ����������			
			"out_trade_no"   => $order_id,//'3',//date('Ymdhms'),     //��Ʒ�ⲿ���׺ţ������֤Ψһ�ԣ�
			"price"          => $amount,           //��Ʒ���ۣ�����۸���Ϊ0��
			"payment_type"   => "1",              //Ĭ��Ϊ1,����Ҫ�޸�
			"quantity"       => "1",              //��Ʒ����������
				
			"logistics_fee"      =>'0.00',        //�������ͷ���
			"logistics_payment"  =>'BUYER_PAY',   //�������ø��ʽ��SELLER_PAY(����֧��)��BUYER_PAY(���֧��)��BUYER_PAY_AFTER_RECEIVE(��������)
			"logistics_type"     =>'EXPRESS',     //�������ͷ�ʽ��POST(ƽ��)��EMS(EMS)��EXPRESS(�������)

			"show_url"       => $show_url,        //��Ʒ�����վ
			"seller_email"   => $seller_email     //�������䣬����
		);
		*/

		$alipay = new alipay_service($parameter,$security_code,$sign_type);
		$action=$alipay->create_url();

		$this->data['action'] = $action;
		$this->id = 'payment';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/alipay_direct.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/alipay_direct.tpl';
		} else {
			$this->template = 'default/template/payment/alipay_direct.tpl';
		}	
		
		
		$this->render();	
	}

	
	// ֧�����غ�Ĵ���
	public function callback() {
		$oder_success = FALSE;

		// ��ȡ�̼���Ϣ
		$this->load->library('encryption');
		$seller_email = $this->config->get('alipay_direct_seller_email');		// �̼�����
		$security_code = $this->config->get('alipay_direct_security_code');	//��ȫ������
		$partner = $this->config->get('alipay_direct_partner');				//�������ID
		$_input_charset = "utf-8"; //�ַ������ʽ  Ŀǰ֧�� GBK �� utf-8
		$sign_type = "MD5"; //���ܷ�ʽ  ϵͳĬ��(��Ҫ�޸�)		
		$transport = 'http';//����ģʽ,����Ը����Լ��ķ������Ƿ�֧��ssl���ʶ�ѡ��http�Լ�https����ģʽ(ϵͳĬ��,��Ҫ�޸�)
		log_result("callback start.");
		
		// ��ȡ֧�������ص�����
		$alipay = new alipay_notify($partner,$security_code,$sign_type,$_input_charset,$transport);
		$verify_result = $alipay->notify_verify();		

		if($verify_result) {   //��֤�ϸ�

		 //��ȡ֧�����ķ�������
			$order_id   = $_POST['out_trade_no'];   //��ȡ֧�������ݹ����Ķ�����

			$this->load->model('checkout/order');
			
			// ��ȡ����ID
			$order_info = $this->model_checkout_order->getOrder($order_id);
		
			// �洢������ϵͳ���ݿ�
			if ($order_info) {
				$order_status_id = $order_info["order_status_id"];

				$alipay_direct_order_status_id = $this->config->get('alipay_direct_order_status_id');
				$alipay_direct_wait_buyer_pay = $this->config->get('alipay_direct_wait_buyer_pay');
				$alipay_direct_wait_buyer_confirm = $this->config->get('alipay_direct_wait_buyer_confirm');
				$alipay_direct_trade_finished = $this->config->get('alipay_direct_trade_finished');
				$alipay_direct_wait_seller_send = $this->config->get('alipay_direct_wait_seller_send');

				if (1 > $order_status_id){
					log_result('order->confirm order_status_id=' . $order_status_id);
					$this->model_checkout_order->confirm($order_id, $alipay_direct_order_status_id);
				}

				// ���⴦������ɵĶ���
				log_result('order_id=' . $order_id . ' order_status_id=' . $order_status_id);
				
				if ($order_status_id != $alipay_direct_trade_finished) {
					log_result("No finished.");					
					
					// ��ȡԭʼ�������ܶ�
					$currency_code = $this->config->get('alipay_direct_currency_code');				//����Ҵ��ţ�CNY��
					$total = $order_info['total'];
					log_result('total=' . $total);
					if($currency_code == ''){
						$currency_code = 'CNY';
					}					
					$currency_value = $this->currency->getValue($currency_code);
					log_result('currency_value=' . $currency_value);
					$amount = $total * $currency_value;
					$amount = number_format($amount,2,'.','');
					log_result('amount=' . $amount);

					// ֧����������
					$total     = $_POST['total_fee'];      // ��ȡ֧�������ݹ������ܼ۸�
					log_result('total_fee=' . $total);
					/*
					$receive_name    =$_POST['receive_name'];    //��ȡ�ջ�������
					$receive_address =$_POST['receive_address']; //��ȡ�ջ��˵�ַ
					$receive_zip     =$_POST['receive_zip'];     //��ȡ�ջ����ʱ�
					$receive_phone   =$_POST['receive_phone'];   //��ȡ�ջ��˵绰
					$receive_mobile  =$_POST['receive_mobile'];  //��ȡ�ջ����ֻ�
					*/
					
					/*
						��ȡ֧��������������״̬,���ݲ�ͬ��״̬���������ݿ� 
						//TRADE_FINISHED(��ʾ�����Ѿ��ɹ�������ͨ�ü�ʱ���ʷ����Ľ���״̬�ɹ���־);
						//TRADE_SUCCESS(��ʾ�����Ѿ��ɹ��������߼���ʱ���ʷ����Ľ���״̬�ɹ���־);
					*/

					log_result("trade_status:" . $_POST['trade_status']);

					if($_POST['trade_status'] == 'WAIT_BUYER_PAY') {
						log_result('==alipay_direct_wait_buyer_pay==');

						//���жϱ�ʾ�������֧�������׹����в����˽��׼�¼����û�и���
					
						//�жϸñʶ����Ƿ����̻���վ���Ѿ����������ɲο������ɽ̡̳��С�3.4�������ݴ�����
						//���û�������������ݶ����ţ�out_trade_no�����̻���վ�Ķ���ϵͳ�в鵽�ñʶ�������ϸ����ִ���̻���ҵ�����
						//���������������ִ���̻���ҵ�����
						if($order_status_id != $alipay_direct_trade_finished && $order_status_id != $alipay_direct_wait_buyer_confirm && $order_status_id != $alipay_direct_wait_seller_send){
							$this->model_checkout_order->update($order_id, $alipay_direct_wait_buyer_pay);							

							echo "success - alipay_direct_wait_buyer_pay";		//�벻Ҫ�޸Ļ�ɾ��
							
							//�����ã�д�ı�������¼������������Ƿ�����
							log_result('success - alipay_direct_wait_buyer_pay');
						}
					}
					else if($_POST['trade_status'] == 'WAIT_SELLER_SEND_GOODS') {
						log_result('==alipay_direct_wait_seller_send==');
						//���жϱ�ʾ�������֧�������׹����в����˽��׼�¼�Ҹ���ɹ���������û�з���
					
						//�жϸñʶ����Ƿ����̻���վ���Ѿ����������ɲο������ɽ̡̳��С�3.4�������ݴ�����
						//���û�������������ݶ����ţ�out_trade_no�����̻���վ�Ķ���ϵͳ�в鵽�ñʶ�������ϸ����ִ���̻���ҵ�����
						//���������������ִ���̻���ҵ�����

						if($order_status_id != $alipay_direct_trade_finished && $order_status_id != $alipay_direct_wait_buyer_confirm){
							$this->model_checkout_order->update($order_id, $alipay_direct_wait_seller_send);

							echo "success - alipay_direct_wait_seller_send";		//�벻Ҫ�޸Ļ�ɾ��
						
							//�����ã�д�ı�������¼������������Ƿ�����
							log_result('success - alipay_direct_wait_seller_send');
						}

						
					}
					else if($_POST['trade_status'] == 'TRADE_FINISHED' ||$_POST['trade_status'] == 'TRADE_SUCCESS') {    //���׳ɹ�����
						//����������Զ������,������ݲ�ͬ��trade_status���в�ͬ����
						$this->model_checkout_order->update($order_id, $alipay_direct_trade_finished);

						echo "success - alipay_direct_trade_finished";		//�벻Ҫ�޸Ļ�ɾ��
						
						//�����ã�д�ı�������¼������������Ƿ�����
						log_result('success - alipay_direct_trade_finished');						

						log_result("finished.");
				    }										
					else {
						echo "fail";
						log_result ("verify_failed");
					}
				}
			}
		}
	}
}
?>