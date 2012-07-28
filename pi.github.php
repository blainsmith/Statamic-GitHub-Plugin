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
    $account = $this->fetch_param('account', 'statamic');
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

  /**
   * Pull a list of all repos
   *
   * Usage:
   * <pre>
   * {{ github:repos account="ericbarnes" }}
	 * {{ name }}
	 * {{ /github:repo }}
   * </pre>
   */
  public function repos() {
    $account  = $this->fetch_param('account', 'statamic');
    try {
	    $data = json_decode(file_get_contents($this->endpoint_url . '/users/' . $account . '/repos'));

	    foreach ($data as $key => $item) {
	    	$ret[$key] = get_object_vars($item);
	    }

	    return $this->parse_loop($this->content, $ret);

	  } catch(Exception $e) {
		  return '';
	  }
  }

  /**
   * Pull a single github repo
   *
   * Usage:
   * <pre>
   * {{ github:repo account="ericbarnes" name="Statamic-GitHub-Plugin" }}
	 * {{ description }}
	 * {{ /github:repo }}
   * </pre>
   */
  public function repo() {
  	$account = $this->fetch_param('account', 'statamic');
  	$repo = $this->fetch_param('repo', 'Plugin-Dribbble');
  	try {
	    $data = json_decode(file_get_contents($this->endpoint_url . '/repos/' . $account.'/'.$repo));
	    // Convert the object into a multi dimension array so we can use it in a loop.
	   	$data = array(0 => (array) $data);

	   	return $this->parse_loop($this->content, $data);

	  } catch(Exception $e) {
		  return '';
	  }
  }

}