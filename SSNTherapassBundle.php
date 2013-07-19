<?php

namespace SSN\TherapassBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SSNTherapassBundle extends Bundle
{
	
	public function boot() {
		$error_manager = $this->container->get('ssn_therapass.error.notifier');
		register_shutdown_function(array($error_manager, 'checkFatalError'));
	}
	
}
