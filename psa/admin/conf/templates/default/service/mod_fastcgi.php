<IfModule mod_fcgid.c>
    <Files ~ (\.fcgi$)>
        SetHandler fcgid-script
        Options +FollowSymLinks +ExecCGI
    </Files>
</IfModule>
