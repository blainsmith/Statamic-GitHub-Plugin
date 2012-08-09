Statamic GitHub Plugin
================================

## Installing
1. Download the zip file (or clone via git) and unzip it or clone the repo into `/_add-ons/`.
2. Ensure the folder name is `github` (Github timestamps the download folder).
3. Enjoy.

_NOTE: Default param values are bold._

## Profile Usage

Calling the plugin from the theme is done with `{{ github:profile account="blainsmith" gists="yes|no" }}`.

## Repos Usage

Calling the plugin from the theme is done with:

    {{ github:repos account="blainsmith" type="*all*|owner|public|private|member" sort="created|updated|pushed|*full_name*" direction="asc|desc" }}
        <h2>{{ name }}</h2>
        <p>{{ description }}</p>
        <hr />
    {{ /github:repos }}

## Single Repo

Calling the plugin from the theme is done with:

    {{ github:repo account="blainsmith" repo="Statamic-GitHub-Plugin" }}
        <h1>{{ name }}</h1>
        <p>{{ description }}</p>
    {{ /github:repo }}

_For Repos please see [GitHub's Repos' API docs](http://developer.github.com/v3/repos/) for full response data._