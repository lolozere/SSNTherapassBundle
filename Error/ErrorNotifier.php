<?php
namespace SSN\TherapassBundle\Error;

use Symfony\Component\Debug\ExceptionHandler;

use Symfony\Component\Debug\Exception\FlattenException;

use Symfony\Component\DependencyInjection\Container;

/**
 * Service pour la gestion des erreurs
 * 
 * @author lolozere
 *
 */
class ErrorNotifier extends ExceptionHandler {
	
	/**
	 * @var Container
	 */
	protected $container;
	
	public function __construct($container, $debug = true, $charset = 'UTF-8') {
		parent::__construct($debug, $charset);
		$this->container = $container;
	}
	
	/**
	 * 
	 * @param string $message
	 * @param string $file
	 * @param integer $line
	 * @return FlattenException
	 */
	private function getFatalErrorMessage($message, $file, $line) {
		$message =  $message . ' / Line ' . $line . ' in ' . $file;
		$message_data = "\n\n--------------------------\nCOOKIES\n--------------------------";
		if (isset($_COOKIE)) {
			foreach($_COOKIE as $key => $val) {
				if (is_array($val)) {
					$message_data .= "\n" . $key . " : " . serialize($val);
				} elseif (is_object($val)) {
					$message_data .= "\n" . $key . " : " . get_class($val);
				} else {
					$message_data .= "\n" . $key . " : " . $val;
				}
			}
		}
		$message_data .= "\n--------------------------\nGET\n--------------------------";
		if (isset($_GET)) {
			foreach($_GET as $key => $val) {
				if (is_array($val)) {
					$message_data .= "\n" . $key . " : " . serialize($val);
				} elseif (is_object($val)) {
					$message_data .= "\n" . $key . " : " . get_class($val);
				} else {
					$message_data .= "\n" . $key . " : " . $val;
				}
			}
		}
		$message_data .= "\n--------------------------\nPOST\n--------------------------";
		if (isset($_POST)) {
			foreach($_POST as $key => $val) {
				if (is_array($val)) {
					$message_data .= "\n" . $key . " : " . serialize($val);
				} elseif (is_object($val)) {
					$message_data .= "\n" . $key . " : " . get_class($val);
				} else {
					$message_data .= "\n" . $key . " : " . $val;
				}
			}
		}
		$message_data .= "\n--------------------------\nSERVER\n--------------------------";
		if (isset($_SERVER)) {
			foreach($_SERVER as $key => $val) {
				if (is_array($val)) {
					$message_data .= "\n" . $key . " : " . serialize($val);
				} elseif (is_object($val)) {
					$message_data .= "\n" . $key . " : " . get_class($val);
				} else {
					$message_data .= "\n" . $key . " : " . $val;
				}
			}
		}
		$message .= $message_data;
		return FlattenException::create(new \Exception($message), 500);
	}
	
	/**
	 * Flush sent email
	 *
	 * From digging some Symfony and SwiftMailer code, I can see that the memory spool is flushed on the kernel.terminate event
	 * that occurs after a response was sent. Here you can force to flush spool
	 */
	public function flushMail() {
		$transport = $this->container->get('mailer')->getTransport();
		if (!$transport instanceof \Swift_Transport_SpoolTransport) {
			return;
		}
		$spool = $transport->getSpool();
		if (!$spool instanceof \Swift_MemorySpool) {
			return;
		}
		$spool->flushQueue($this->container->get('swiftmailer.transport.real'));
	}
	
	protected function createHtmlView($exception) {
		$content = '';
		$title = '';
		try {
			if (!$exception instanceof FlattenException) {
				$exception = FlattenException::create($exception);
			}
			switch ($exception->getStatusCode()) {
				case 404:
					$title = 'Page could not be found.';
					break;
				default:
					$title = 'Something went wrong.';
			}
	
			$content = $this->getContent($exception);
		} catch (\Exception $e) {
			$title = sprintf('Exception thrown when handling an exception (%s: %s)', get_class($exception), $exception->getMessage());
		}
		
		// Decorate accessible
		$class = new \ReflectionClass(get_class($this));
		$decorate = $class->getMethod('decorate');
		$decorate->setAccessible(true);
		
		return $decorate->invokeArgs($this, array($content, $this->getStylesheet($exception)));
	}
	
	/**
	 * 
	 * @param \Exception $exception
	 */
	public function sendMailError(FlattenException $exception) {
		$message = \Swift_Message::newInstance()
			->setSubject('SSN - Error')
			->setFrom('contact@soletic.org')
			->setTo($this->container->getParameter('error_notification_mail'))
			->setBody($this->createHtmlView($exception), 'text/html');
		$r = $this->container->get('mailer')->send($message);
		$this->flushMail();
	}
	
	public function checkFatalError() {
		$error = error_get_last();
		if ($this->container->getParameter('error_notification')) {
			if ($error !== NULL && !in_array($error['type'], array(
					E_WARNING, E_NOTICE, E_DEPRECATED, E_USER_DEPRECATED, E_USER_ERROR, E_USER_NOTICE, E_USER_WARNING)) // Exclude error no fatal
				) {
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
				$exception = $this->getFatalErrorMessage($error['message'], $error['file'], $error['line']);
				mail($this->container->getParameter('error_notification_mail'), 'SSN - Error', $this->createHtmlView($exception, $headers));
			}
		}
	}
}