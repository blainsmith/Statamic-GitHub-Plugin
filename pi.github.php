<?php
class Plugin_github extends Plugin {

  var $meta = array(
    'name'       => 'GitHub',
    'version'    => '0.1',
    'author'     => 'Blain Smith',
    'author_url' => 'http://blainsmith.com'
  );
  
  function __construct() {
    parent::__construct();
    $this->endpoint_url  = 'https://api.github.com';
    $this->public_url  = 'http://github.com';
    $this->gists_url  = 'http://github.com';
  }
  
  public function profile() {
    $account  = $this->fetch_param('account', 'statamic');
    $gists = $this->fetch_param('gists', true, false, true);
    
    try {
	    $data = json_decode(file_get_contents($this->endpoint_url . '/users/' . $account));
	    
	    $output = '<div class="github">
		    <ul class="profile">
		    	<li><a href="' . $this->public_url . '/' . $account . '/followers"><span class="count">' . $data->followers . '</span> <span class="label">followers</span></a></li>
		    	<li><a href="' . $this->public_url . '/' . $account . '"><span class="count">' . $data->public_repos . '</span> <span class="label">public repos</span></a></li>';

		  if($gists) $output .= '  	<li><a href="' . $this->gists_url . '/' . $account . '"><span class="count">' . $data->public_gists . '</span> <span class="label">public gists</span></a></li>';

		  $output .= '  </ul>
		  </div>';
	    
	    return $output;
	  } catch(Exception $e) {
		  return '';
	  }
  }

  public function repos() {
    $account  = $this->fetch_param('account', 'statamic');

    try {
	    $data = json_decode(file_get_contents($this->endpoint_url . '/users/' . $account . '/repos'));
	    
	    $output = '<div class="github">
		    <ul class="repos">';
		    
		  for($r = 0; $r < sizeof($data); $r++) {
		    $output .= '  	<li><a href="' . $data[$r]->html_url . '">' . $data[$r]->name . '</a></li>';
		  }
		  
		  $output .= '  </ul>
		  </div>';
	    
	    return $output;
	  } catch(Exception $e) {
		  return '';
	  }
  }
    
}