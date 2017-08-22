<?php

class pluginTtjCopyright extends Plugin {

	private $enable;
	
	//default enabled on the end
	private $enableOnStart;
	
    private function ttjCopyright()
    {
        $ret = '<div class="ttjCopyright">' .Sanitize::htmlDecode($this->getDbField('copyrightText')) . '</div>';
        return $ret;
    }

	public function init()
	{
		$this->dbFields = array(
            'enablePages'       =>0,
            'enablePosts'       =>0,
		//	'enableOnStart'     =>0,
			'copyrightText'     =>''
		);
	}

	function __construct()
	{
		parent::__construct();

		global $Url;

		$this->enable        = false;
		$this->enableOnStart = false;

		if( $this->getDbField('copyrightText') != '' ) {
			if( $this->getDbField('enablePosts') && ($Url->whereAmI()=='post') ) {
					$this->enable = true;
				/*	if ($this->getDbField('enableOnStart')) {
						$this->enableOnStart = true;
					}*/
			}
			elseif( $this->getDbField('enablePages') && ($Url->whereAmI()=='page') ) {
					$this->enable = true;
                /*
					if ($this->getDbField('enableOnStart')) {
						$this->enableOnStart = true;
					}*/
			}
		}
	}

	public function form()
	{
		global $Language;

        $html = '<div>';
        $html .= '<input type="hidden" name="enablePages" value="0">';
        $html .= '<input name="enablePages" id="jsenablePages" type="checkbox" value="1" '.($this->getDbField('enablePages')?'checked':'').'>';
        $html .= '<label class="forCheckbox" for="jsenablePages">'.$Language->get('Enable copyrights on pages').'</label>';
        $html .= '</div>';

        $html .= '<div>';
        $html .= '<input type="hidden" name="enablePosts" value="0">';
        $html .= '<input name="enablePosts" id="jsenablePosts" type="checkbox" value="1" '.($this->getDbField('enablePosts')?'checked':'').'>';
        $html .= '<label class="forCheckbox" for="jsenablePosts">'.$Language->get('Enable copyrights on posts').'</label>';
        $html .= '</div>';

   /*     $html .= '<div>';
        $html .= '<input type="hidden" name="enableOnStart" value="0">';
        $html .= '<input name="enableOnStart" id="jsenableOnStart" type="checkbox" value="1" '.($this->getDbField('enableOnStart')?'checked':'').'>';
        $html .= '<label class="forCheckbox" for="jsenableOnStart">'.$Language->get('Enable copyrights on the beginning of the post or page').'</label>';
        $html .= '</div>';*/

		$html .= '<div>';
		$html .= '<label for="jscopyrightText">'.$Language->get('copyrights-label').'</label>';
		$html .= '<textarea placeholder="'.$Language->get('copyrights-text').'" id="jsa" type="text" name="copyrightText">'.$this->getDbField('copyrightText') .'</textarea>';
		$html .= '</div>';

		return $html;
	}

	public function siteHead()
    {
        // Path plugin.
        $pluginPath = $this->htmlPath();

        // Copyright css
        $html = '<link rel="stylesheet" href="'.$pluginPath.'css/ttjCopyright.css">';

        return $html;
    }

	public function postBegin()
	{
		return $this->enableCopyrightOnStart();
	}

	public function pageBegin()
	{
		return $this->enableCopyrightOnStart();
    }
	
	public function postEnd()
	{
        echo $this->enableCopyrightOnEnd();
	}

	public function pageEnd()
	{
	    echo $this->enableCopyrightOnEnd();
    }
	
	private function enableCopyrightOnEnd() {
		if( ($this->enable ) && ( !$this->enableOnStart)) {
			return $this->ttjCopyright();
		}
		return false;
	}
	
	private function enableCopyrightOnStart() {
		if( ( $this->enable ) && ( $this->enableOnStart)) {
			return $this->ttjCopyright();
		}
		return false;
	}
	

}
