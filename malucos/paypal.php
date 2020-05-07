<?php


  
  require_once("backend/functions.php");
  require_once("backend/paypal.class.php");
  dbconn(true);
  loggedinonly();
  
  $p = new paypal_class;            

  //$p->paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
  $p->paypal_url = "https://www.paypal.com/cgi-bin/webscr";     
            
  $this_script = $site_config['SITEURL'] . "/paypal.php";
  
  if (empty($_GET["action"]))
      $_GET["action"] = "process";
      
  switch($_GET["action"])
  {
      case "process":
      $p->add_field("business", "donativo@malucos-share.org");
      $p->add_field("return", $this_script . "?action=success");
      $p->add_field("cancel_return", $this_script . "?action=cancel");
      $p->add_field("notify_url", $this_script . "?action=ipn");
      $p->add_field("item_name", $_POST["item_name"]);
      $p->add_field("amount", isset($_POST["amount"]) ? $_POST["amount"] : 10);
      $p->add_field("currency_code", "BRL");
      $p->add_field("custom", isset($_POST["userid"]) ? $_POST["userid"] : 0);
      $p->submit_paypal_post();
      break;
      
      case "success":
      stdhead("Obrigado por sua compra");
      begin_framec("Obrigado por sua compra");
      echo("<h3>Obrigado por sua compra.</h3>");
      end_framec();
      stdfoot();
      break;

      case "cancel":
      stdhead("Cancela pedido");
      begin_framec("Pedido cancelado");
      echo("<h3>Seu pedido foi cancelado.</h3>");
      end_framec();
      stdfoot();
      break;
      
      case "ipn":
      if ($p->validate_ipn())
      {
          $subject = "Instant Pagamento Notificação - Pagamento Recebido";
          $to = "donativos@malucos-share.org";
          $body  = "Uma notificação de pagamento imediato foi recebido com sucesso\n";
          $body .= "for " . $p->ipn_data["item_name"] . " on " . get_date_time();
          $body .= "\n\n Details:\n";
          
          foreach ($p->ipn_data as $key => $value)
          {
              $body .= "\n$key: $value";
          }
          
          mail($to, $subject, $body, "From: " . $site_config["SITENAME"] . " <" . $site_config["SITEEMAIL"] . ">");
          
          $payment_amount = $p->ipn_data["mc_gross"];

          if (is_valid_id($p->ipn_data["custom"]))
          {
              $query = mysql_query("SELECT * FROM `users` WHERE `id` = '" . $p->ipn_data["custom"] . "'");
              $row   = mysql_fetch_array($query);
              
              mysql_query("UPDATE `users` SET `donated` = `donated` + '" . $payment_amount . "' WHERE `id` = '" . $p->ipn_data["custom"] . "'");
              mysql_query("UPDATE `site_settings` SET donations =  donations + '$payment_amount'");
          

          }
      } 
      break;
  }

?>