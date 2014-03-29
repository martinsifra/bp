<?php

namespace App\Presenters;

use Nette,
	App\Model;


/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{
    /** @var \WebLoader\LoaderFactory @inject */
    public $webLoader;
    
    /** @var \App\Components\ISignOutControlFactory @inject */
    public $signOutControlFactory;

    
    public function checkRequirements($element)
    {     
        $secured = $element->getAnnotation('secured');
        $resource = $element->getAnnotation('resource');
        $privilege = $element->getAnnotation('privilege');

        if ($secured) {
            if (!$this->user->isLoggedIn()) {
                $this->redirect('Sign:in', array('backlink' => $this->storeRequest()));
            } elseif (!$this->user->isAllowed($resource, $privilege)) {
                throw new Nette\Application\ForbiddenRequestException;
            }
        }
    }
    
    
    
    public function createComponentSignOut()
    {
        return $this->signOutControlFactory->create();
    }


    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    /** @return CssLoader */
    protected function createComponentCss()
    {
        $css =  $this->webLoader->createCssLoader('default')
                ->setMedia('screen,projection,tv')
                ->setType(null);
        return $css;
    }

    /** @return CssLoader */
    protected function createComponentCssGrido()
    {
        $css =  $this->webLoader->createCssLoader('grido')
                ->setMedia('screen,projection,tv')
                ->setType(null);
        return $css;
    }

    /** @return JavaScriptLoader */
    protected function createComponentJs()
    {
        return $this->webLoader->createJavaScriptLoader('default');
    }
    
    /** @return JavaScriptLoader */
    protected function createComponentJsGrido()
    {
        return $this->webLoader->createJavaScriptLoader('grido');
    }
}
