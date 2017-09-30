<?php

class pluginTtjRelatedPosts extends Plugin {

private $enable = false;

	public function init()
	{
		$this->dbFields = array(
			'label'=>'Latest posts',
			'amount'=>5
		);
	}

	function __construct()
    {
        parent::__construct();

        global $Url;

        if($Url->whereAmI()=='post' ) {
            $this->enable = true;
        }
    }

    public function form()
	{
		global $Language;

		$html  = '<div>';
		$html .= '<label>'.$Language->get('Plugin label').'</label>';
		$html .= '<input name="label" id="jslabel" type="text" value="'.$this->getDbField('label').'">';
		$html .= '</div>';

		$html .= '<div>';
		$html .= '<label>'.$Language->get('Amount of posts').'</label>';
		$html .= '<input name="amount" id="jsamount" type="text" value="'.$this->getDbField('amount').'">';
		$html .= '</div>';

		return $html;
	}

	public function postEnd()
	{
        global $Post;

	    $tags = $Post->tags(true);

	    //initialize empty array
        $allPosts = array();
      //  shuffle($tags);
        foreach ($tags as $tag) {

            $tag = preg_replace('/\s+/', '-', $tag); 

            $posts = buildPostsForPage(0, $this->getDbField('amount') + 1, true, strtolower($tag)); //stestuj to

          //  echo count($posts);

            foreach ($posts as $postItem) {
                if ($postItem->slug() !== $Post->slug()) {
                    $allPosts[$postItem->slug()] = $postItem;
                }
            }
          //  array_push($allPosts,$posts);
        }

      //  echo count($allPosts );

        //mix array with post
        shuffle($allPosts);


        //and get only the first X post objects from it
        $allPosts = array_slice($allPosts,0,$this->getDbField('amount'));

        $html  = '<div class="plugin plugin-related-posts">';
        $html  .= '<hr />';
        // Print the label if not empty.
        $label = $this->getDbField('label');
        if( !empty($label) ) {
            $html .= '<h4 class="plugin-title">'.$label.'</h4>';
        }

        $html .= '<div class="plugin-content">';
        $html .= '<ul>';

        foreach ($allPosts as $item) {
            $html .= '<li>';
            $html .= '<a href="'.$item->permalink().'">'.$item->title() .'</a>';
            $html .= '</li>';
        }

		$html .= '</ul>';
 		$html .= '</div>';
 		$html .= '</div>';

		return count($allPosts)>0 && $this->enable ? $html : '';
	}
}