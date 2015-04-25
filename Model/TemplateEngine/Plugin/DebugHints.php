<?php
/**
 * Plugin for the template engine factory that makes a decision of whether to activate debugging hints or not
 *
 * (c) Anton Ohorodnyk <anton@ohorodnyk.name>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// @codingStandardsIgnoreFile

namespace Magento\DebugComment\Model\TemplateEngine\Plugin;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\State;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\ScopeInterface;

class DebugHints
{
    /**#@+
     * XPath of configuration of the debugging hints
     */
    const XML_PATH_DEBUG_TEMPLATE_HINTS = 'dev/debug/template_hints_comment';
    /**#@-*/

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var State
     */
    private $appState;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param State $appState
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        State $appState
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->appState = $appState;
    }

    /**
     * Wrap template with the debugging hints in comments
     *
     * @param Template $subject
     * @param string $result
     *
     * @return string
     */
    public function afterToHtml(Template $subject, $result)
    {
        if ($this->scopeConfig->getValue(self::XML_PATH_DEBUG_TEMPLATE_HINTS, ScopeInterface::SCOPE_STORE) &&
            $this->appState->getMode() === State::MODE_DEVELOPER
        ) {
            $name = $subject->getNameInLayout();
            $template = $subject->getTemplateFile();
            $class = get_class($subject);

            $result = "<!-- BEGIN {$name} using {$template} \n" . $class . '-->'
                . $result
                . "<!-- END {$name} using {$template} -->";
        }

        return $result;
    }
}
