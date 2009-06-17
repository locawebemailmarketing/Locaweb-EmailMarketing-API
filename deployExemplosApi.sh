#!/bin/bash
DIR_SRC='emailmktExemplosApi'
DIR_DEPLOY='/home/httpd/html/newsletter/exemplosapi'
mv -f $DIR_SRC old_$DIR_SRC
git clone git://git.locaweb.com.br/email-marketing-exemplo-apis/mainline.git $DIR_SRC
cd $DIR_SRC
zip -r php.zip php/
zip -r java.zip java/
zip -r ruby.zip ruby/
zip -r csharp.zip c#/
mkdir -p $DIR_DEPLOY
mv *.zip $DIR_DEPLOY
/bin/echo 'deploy realizado'