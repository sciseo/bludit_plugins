<?php

class pluginTtjTwitterWidget extends Plugin {

    private $enable;
	
    private function ttjTwitterWidgetProfil()
    {

        global $Language;

        $html = '<section id="twitter">';
	    $html .= '<header>';
		$html .= '<h2>'  .strtoupper($this->getDbField('twitterLabel')).  '</h2>';
		$html .= '<a class="twitter-timeline" data-lang="' .$Language->getCurrentLocale() . '" data-tweet-limit="'.$this->getDbField('dataLimit').'" href="https://twitter.com/'.$this->getDbField('twitterUsername').'">Tweets by '.$this->getDbField('twitterUsername').'</a>';
        $html .= '</header>';
        $html .= '</section>';

        return $html;
    }

	public function init()
	{
        $this->dbFields = array(
            'twitterLabel'    => 'Tweety',
            'dataLimit'       =>'5',
            'twitterUsername'       =>'',
        );
	}

	function __construct()
	{
		parent::__construct();

        if ($this->getDbField('twitterUsername') !== '') {
            $this->enable = true;
        }
	}


    public function form()
    {
        global $Language;

        $html = '<div>';
        $html .= '<label for="jstwitterLabel">'.$Language->get('set-twitter-label').'</label>';
        $html .= '<input name="twitterLabel" id="jstwitterLabel" type="text" value="'.$this->getDbField('twitterLabel').'">';
        $html .= '</div>';

        $html .= '<div>';
        $html .= '<label for="jsdataLimit">'.$Language->get('set-data-limit').'</label>';
        $html .= '<input name="dataLimit" id="jsdataLimit" type="text" value="'.$this->getDbField('dataLimit').'">';
        $html .= '</div>';

        $html .= '<div>';
        $html .= '<label for="jstwitterUsername">'.$Language->get('name-of-twitter-user').'</label>';
        $html .= '<input name="twitterUsername" id="jstwitterUsername" type="text" value="'.$this->getDbField('twitterUsername').'">';
        $html .= '</div>';

        return $html;
    }

	public function siteSidebar()
    {
        return $this->enable ? $this->ttjTwitterWidgetProfil() : null;
    }

     function siteBodyEnd()
     {
         return $this->enable ? '<script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>' : null;
     }
}
