# Use this command everytime you change something in your .env file.
source ./.env
echo " FriendsOf 🥧"
echo " ____        _     _"
echo "|  _ \      | |   | |"
echo "| |_) | __ _| |__ | |__   __ _ "
echo "|  _ < / _  | '_ \| '_ \ / _  |"
echo "| |_) | (_| | |_) | |_) | (_| |"
echo "|____/ \__,_|_.__/|_.__/ \__,_| // Dockerfiles"
echo " "
echo "🏃 Building config file..."
echo "💡 App name: $APP_NAME"
echo "🌐 Website: $NGINX_HOST (remember to add it to you hosts file)"


# Without this command I'm not able to inject ${APP_NAME} into mutange template
# using envsubst.
export APP_NAME=$APP_NAME

envsubst < ./mutagen.template.yml > ./mutagen.yml

echo "✔️  Done, exec mutagen project start to run!"