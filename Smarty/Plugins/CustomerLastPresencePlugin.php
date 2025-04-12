<?php

/*
 * This file is part of the Thelia package.
 * http://www.thelia.net
 *
 * (c) OpenStudio <info@thelia.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CustomerLastPresence\Smarty\Plugins;

use CustomerLastPresence\Model\CustomerLastPresenceQuery;
use Symfony\Component\HttpFoundation\RequestStack;
use Thelia\Core\Security\SecurityContext;
use TheliaSmarty\Template\AbstractSmartyPlugin;
use TheliaSmarty\Template\SmartyPluginDescriptor;

class CustomerLastPresencePlugin extends AbstractSmartyPlugin
{
    protected $request;
    protected $securityContext;

    public function __construct(RequestStack $requestStack, SecurityContext $securityContext)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->securityContext = $securityContext;
    }

    /**
     * @return SmartyPluginDescriptor[] an array of SmartyPluginDescriptor
     */
    public function getPluginDescriptors()
    {
        return [
            new SmartyPluginDescriptor('function', 'customerLastPresence', $this, 'getCustomerLastPresence'),
        ];
    }

    public function getCustomerLastPresence($params)
    {
        $output = '';
        if (isset($params['id'])) {
            $search = CustomerLastPresenceQuery::create()
                ->findOneByCustomerId($params['id']);
            if (null !== $search) {
                $output = $search->getDate()->format('Y-m-d H:i:s');
            } else {
                $output = "-";
            }
        }

        return $output;
    }
}
