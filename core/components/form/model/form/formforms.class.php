<?php

	/**
	 * Form
	 *
	 * Copyright 2017 by Oene Tjeerd de Bruin <modx@oetzie.nl>
	 *
	 * Form is free software; you can redistribute it and/or modify it under
	 * the terms of the GNU General Public License as published by the Free Software
	 * Foundation; either version 2 of the License, or (at your option) any later
	 * version.
	 *
	 * Form is distributed in the hope that it will be useful, but WITHOUT ANY
	 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
	 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
	 *
	 * You should have received a copy of the GNU General Public License along with
	 * Form; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
	 * Suite 330, Boston, MA 02111-1307 USA
	 */
	 
	class FormForms extends xPDOSimpleObject {
		/**
		 * @access public.
		 * @param String Array.
		 * @return String.
		 */
		public function encrypt($data) {
			return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($this->getEncryptkey()), $data, MCRYPT_MODE_CBC, md5(md5($this->getEncryptkey()))));
    	}
    	
    	/**
		 * @access public.
		 * @param String String.
		 * @return Array.
		 */
		public function decrypt($data) {
			return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($this->getEncryptkey()), base64_decode($data), MCRYPT_MODE_CBC, md5(md5($this->getEncryptkey()))), "\0");
    	}

		/**
		 * @access public.
		 * @return String.
		 */
		public function getEncryptkey() {
			$key = $this->xpdo->getOption('form.encrypt_key', null, null, false);
			
	        if (false === $key || empty($key)) {
		        $key = $this->xpdo->site_id;
		        
		        $criterea = array(
			    	'key' => 'form.encrypt_key'
		        );
		        
		        if (null === ($setting = $this->xpdo->getObject('modSystemSetting', $criterea))) {
			        $setting = $this->xpdo->newObject('modSystemSetting');
			        $setting->fromArray(array(
				        'key'		=> 'form.encrypt_key',
				        'namespace'	=> 'form'
			        ));
		        }
		        
		        $setting->set('value', $key);
		     
				$setting->save();
		    }
	        
	        return $key;
		}
	}

?>