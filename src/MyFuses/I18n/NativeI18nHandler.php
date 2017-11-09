<?php
/**
 * MyFuses Framework (http://myfuses.candango.org)
 *
 * This product includes software developed by the Fusebox Corporation
 * (http://www.fusebox.org/).
 *
 * @link      http://github.com/candango/myfuses
 * @copyright Copyright (c) 2006 - 2017 Flavio Garcia
 * @license   https://www.apache.org/licenses/LICENSE-2.0  Apache-2.0
 */

namespace Candango\MyFuses\I18n;

use Candango\MyFuses\Controller;
use Candango\MyFuses\Util\FileHandler;

/**
 * MyFuses Native I18n Handler class - NativeI18nHandler.php
 *
 * Utility to handle usual I18n operations. This class is a native
 * implementation don't need any other lib to work.
 *
 * @category   I18n
 * @package    Candango.MyFuses.I18n
 * @author     Flavio Garcia <piraz at candango.org>
 * @since      38b53c53441bab6b440803e69d06f0aef51fa3a2
 */
class NativeI18nHandler extends I18nHandler
{
    public function setLocale()
    {
    }

    public function storeFiles()
    {
        if (I18NContext::mustStore()) {
            $path = Controller::getApplication()->getParsedPath();
            $path = FileHandler::sanitizePath($path . 'I18n');

            if (!file_exists($path)) {
                FileHandler::createPath($path);
            }

            $fileName = $path . "locale.data.php";

            $data = "<?php\nreturn unserialize('";

            $context = I18NContext::getContext();
            $context['last_load_time'] = I18NContext::getTime();

            $data .= str_replace("'", "\'", serialize($context));
            $data .= "');";

            FileHandler::writeFile($fileName, $data);
        }
    }
}
