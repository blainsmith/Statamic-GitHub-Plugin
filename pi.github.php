<?php
class Plugin_github extends Plugin {

  var $meta = array(
    'name'       => 'GitHub',
    'version'    => '0.2',
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
      $data = json_decode($this->_curl($this->endpoint_url . '/users/' . $account));

      $output = '<div class="github">
        <ul class="profile">
          <li><a href="' . $this->public_url . '/' . $account . '/followers"><span class="count">' . $data->followers . '</span> <span class="label">followers</span></a></li>
          <li><a href="' . $this->public_url . '/' . $account . '"><span class="count">' . $data->public_repos . '</span> <span class="label">public repos</span></a></li>';

      if($gists) $output .= '   <li><a href="' . $this->gists_url . '/' . $account . '"><span class="count">' . $data->public_gists . '</span> <span class="label">public gists</span></a></li>';

      $output .= '  </ul>
      </div>';

      return $output;
    } catch(Exception $e) {
      return '';
    }
  }

  /**
   * Pull a list of repos
   *
   * Usage:
   * <pre>
   * {{ github:repos account="blainsmith" type="all|owner|public|private|member" sort="created|updated|pushed|full_name" direction="asc|desc" }}
   * {{ name }}
   * {{ /github:repo }}
   * </pre>
   */
  public function repos() {
    $account = $this->fetch_param('account', 'statamic');
    $type = $this->fetch_param('type', '');
    $sort = $this->fetch_param('sort', '');
    $direction = $this->fetch_param('direction', '');

    $params = array();
    if($type != ''){ array_push($params, 'type=' . $type); }
    if($sort != ''){ array_push($params, 'sort=' . $sort); }
    if($direction != ''){ array_push($params, 'direction=' . $direction); }
    $params = implode("&", $params);

    try {
      $data = json_decode($this->_curl($this->endpoint_url . '/users/' . $account . '/repos?' . $params));

      foreach ($data as $key => $item) {
        $ret[$key] = get_object_vars($item);
      }

      return Parse::tagLoop($this->content, $ret);

    } catch(Exception $e) {
      return '';
    }
  }

  /**
   * Pull a single github repo
   *
   * Usage:
   * <pre>
   * {{ github:repo account="blainsmith" name="Statamic-GitHub-Plugin" }}
   * {{ description }}
   * {{ /github:repo }}
   * </pre>
   */
  public function repo() {
    $account = $this->fetch_param('account', 'statamic');
    $repo = $this->fetch_param('repo', 'Plugin-Dribbble');
    //try {
      $data = json_decode($this->_curl($this->endpoint_url . '/repos/' . $account.'/'.$repo));
      // Convert the object into a multi dimension array so we can use it in a loop.
      $data = array(0 => (array) $data);

      return Parse::tagLoop($this->content, $data);

    // } catch(Exception $e) {
    //   return '';
    // }
  }

  private function _curl($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch,CURLOPT_USERAGENT, 'Statamic GitHub Plugin (https://github.com/blainsmith/Statamic-GitHub-Plugin)');
    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
  }

}
