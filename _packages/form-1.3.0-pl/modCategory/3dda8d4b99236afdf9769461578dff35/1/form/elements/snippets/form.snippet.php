<?php
/**
 * Form
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <modx@oetzie.nl>
 */

$class = $modx->loadClass('FormSnippetForm', $modx->getOption('form.core_path', null, $modx->getOption('core_path') . 'components/form/') . 'model/form/snippets/', false, true);

if ($class) {
    $instance = new $class($modx);

    if ($instance instanceof FormSnippets) {
        return $instance->run($scriptProperties);
    }
}

return '';