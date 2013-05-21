{*?template charset=UTF-8*}
{* Have a look at https://github.com/sebarmeli/JS-Redirection-Mobile-Site for redirection configuration *}
{ezscript_require(array( 'redirection_mobile.js' ) )}
{def $mobile_url            = ezini( 'RedirectUrl', 'mobile', 'owmobile.ini' )|ezurl('no')
     $ready_mobile_url      = cond($mobile_url|contains('http://')  , $mobile_url|extract(7) ,
                                   $mobile_url|contains('https://') , $mobile_url|extract(8) ,
                                   $mobile_url)
     $mobile_scheme         = cond($mobile_url|contains('https://') , 'https' , false())
     $noredirection_param   = ezini( 'JSRedirectionParams', 'noredirection_param', 'owmobile.ini' )|trim()
     $cookie_hours          = ezini( 'JSRedirectionParams', 'cookie_hours', 'owmobile.ini' )|trim()
     $tablet_redirection    = ezini( 'JSRedirectionParams', 'tablet_redirection', 'owmobile.ini' )|trim()
     $keep_path             = ezini( 'JSRedirectionParams', 'keep_path', 'owmobile.ini' )|trim()
     $keep_query            = ezini( 'JSRedirectionParams', 'keep_query', 'owmobile.ini' )|trim()}
<script type="text/javascript">
    SA.redirection_mobile ({ldelim}
        {if $ready_mobile_url|ne('')}mobile_url : '{$ready_mobile_url}'{/if}
        {if $mobile_scheme}, mobile_scheme : '{$mobile_scheme}'{/if}
        {if $noredirection_param|ne('')}, noredirection_param : '{$noredirection_param}'{/if}
        {if $cookie_hours|ne('')}, cookie_hours : '{$cookie_hours}'{/if}
        {if $tablet_redirection|ne('')}, tablet_redirection : '{$tablet_redirection}'{/if}
        {if $keep_path|ne('')}, keep_path : {$keep_path}{/if}
        {if $keep_query|ne('')}, keep_query : {$keep_query}{/if}
    {rdelim});
</script>
{undef}