#!/usr/bin/env bash

# Default value of ENABLE_LETS_ENCRYPT
if [[ -z "${ENABLE_LETS_ENCRYPT}" ]]; then
    ENABLE_LETS_ENCRYPT=FALSE
fi

# Make it upper case
ENABLE_LETS_ENCRYPT=${ENABLE_LETS_ENCRYPT^^}

# Install Let's Encrypt if requested
if [ "${ENABLE_LETS_ENCRYPT}" = "TRUE" ]; then

    # Install Let's Encrypt if not already installed
    # Trigger a renewal attempt if already installed
    if [ ! -f /usr/local/sbin/certbot-auto ]; then

        # Download CertBot
        wget https://dl.eff.org/certbot-auto
        chmod a+x certbot-auto
        mv certbot-auto /usr/local/sbin/

        # Stop Apache
        /usr/sbin/apachectl stop

        # Get an SSL Certificate
        /usr/local/sbin/certbot-auto certonly --no-bootstrap --no-self-upgrade --agree-tos --standalone -d staging-mcgrath.tmp.4mation.com.au -m draco+mcgrath@4mation.com.au -n

    else

        # Stop Apache
        /usr/sbin/apachectl stop

        # Renew SSL Certificate
        /usr/local/sbin/certbot-auto renew --no-bootstrap --no-self-upgrade -n

    fi

    # Enable Let's Encrypt SSL
    sed -i 's/##SSLCertificateFile/SSLCertificateFile/g' /etc/httpd/conf.d/ssl.conf
    sed -i 's/##SSLCertificateKeyFile/SSLCertificateKeyFile/g' /etc/httpd/conf.d/ssl.conf
    sed -i 's/##SSLCertificateChainFile/SSLCertificateChainFile/g' /etc/httpd/conf.d/ssl.conf

    # Disable Self-Signed SSL
    sed -i 's/ SSLCertificateFile        "\/etc\/pki\/tls\/certs\/server.crt"/ #SSLCertificateFile        "\/etc\/pki\/tls\/certs\/server.crt"/g' /etc/httpd/conf.d/ssl.conf
    sed -i 's/ SSLCertificateKeyFile     "\/etc\/pki\/tls\/certs\/server.key"/ #SSLCertificateKeyFile     "\/etc\/pki\/tls\/certs\/server.key"/g' /etc/httpd/conf.d/ssl.conf

    # Restart Apache
    /usr/sbin/apachectl graceful

fi


