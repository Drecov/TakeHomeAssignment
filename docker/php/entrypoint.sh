until nc -z -v -w30 mysql 3306
do
  echo "Aguardando MySQL estar disponível..."
  sleep 5
done

