
echo "Switching Branch Main........................................"
git checkout main

echo "Adding to git........................................"
git add .

echo "Enter commiting Message........................................"
read Msg

echo "commiting with message\"$Msg\" ...................."
git commit -m "$Msg"

echo "git pushing to GitHub........................................"
git push


echo "Deploying Files to Server........................................"
scp -r * root@137.184.179.113:/var/www/iamredapple.com/php


echo "Done!! ........................................"