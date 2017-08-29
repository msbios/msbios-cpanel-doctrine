<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 *
 */

namespace MSBios\CPanel\Doctrine\Controller;

use MSBios\CPanel\Mvc\Controller\ActionControllerInterface;
use MSBios\Guard\GuardAwareInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use Zend\View\Model\ViewModel;

/**
 * Class IndexController
 * @package MSBios\CPanel\Doctrine\Controller
 */
class IndexController extends AbstractActionController implements
    ActionControllerInterface,
    GuardAwareInterface,
    ResourceInterface
{
    /**
     * @return ViewModel
     */
    public function indexAction()
    {
        return new ViewModel([]);
    }

    /**
     * Returns the string identifier of the Resource
     *
     * @return string
     */
    public function getResourceId()
    {
        return \MSBios\CPanel\Controller\IndexController::class;
    }
}