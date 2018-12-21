<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\CPanel\Doctrine\Controller;

use MSBios\CPanel\Controller\ThemeController as DefaultThemeController;
use MSBios\CPanel\Doctrine\Mvc\Controller\AbstractActionController;
use MSBios\Resource\Doctrine\Entity\Theme;

/**
 * Class ThemeController
 * @package MSBios\CPanel\Doctrine\Controller
 */
class ThemeController extends AbstractActionController
{
    /**
     * @inheritdoc
     *
     * @return string
     */
    public function getResourceId()
    {
        return DefaultThemeController::class;
    }

    /**
     * @inheritdoc
     *
     * @return mixed|Theme
     */
    protected static function factory()
    {
        return new Theme;
    }
}
