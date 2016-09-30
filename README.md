# Tietokantasovelluksen esittelysivu

# Asennusohje
## Lataa composer (https://getcomposer.org/download/)
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('SHA384', 'composer-setup.php') === 'e115a8dc7871f15d853148a7fbac7da27d6c0030b848d9b3dc09e2a0388afed865e6a3d6b3c0fad45c48e2b5fc1196ae') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"

##Asenna Composer
php composer.phar install

##Konfiguroi tietokanta
nano config/database.php

##Luo tietokantataulut
sql/create_tables.sql


# Käyttäjät

Käyttäjä 1:
* Käyttäjätunnus: tsoha
* Salasana: tsoha

Käyttäjä 2:
* Käyttäjätunnus: valtteri.korolainen@helsinki.fi
* Salasana: tsoha

Käyttäjä 3:
* Käyttäjätunnus: otheruser@korolainen.fi
* Salasana: tsoha


## Yleisiä linkkejä:

* http://valkorol.users.cs.helsinki.fi/tsoha/
* http://valkorol.users.cs.helsinki.fi/tsoha/signup
* http://valkorol.users.cs.helsinki.fi/tsoha/search
* http://valkorol.users.cs.helsinki.fi/tsoha/products
* http://valkorol.users.cs.helsinki.fi/tsoha/shoppinglists
* http://valkorol.users.cs.helsinki.fi/tsoha/shops
* http://valkorol.users.cs.helsinki.fi/tsoha/groups
* http://valkorol.users.cs.helsinki.fi/tsoha/products/new
* http://valkorol.users.cs.helsinki.fi/tsoha/shoppinglists/new
* http://valkorol.users.cs.helsinki.fi/tsoha/shops/new
* http://valkorol.users.cs.helsinki.fi/tsoha/groups/new
* http://valkorol.users.cs.helsinki.fi/tsoha/products/product/1
* http://valkorol.users.cs.helsinki.fi/tsoha/shoppinglists/shoppinglist/1
* http://valkorol.users.cs.helsinki.fi/tsoha/shops/shop/1
* http://valkorol.users.cs.helsinki.fi/tsoha/groups/group/1?edit=true
* http://valkorol.users.cs.helsinki.fi/tsoha/products/product/1?edit=true
* http://valkorol.users.cs.helsinki.fi/tsoha/shoppinglists/shoppinglist/1?edit=true
* http://valkorol.users.cs.helsinki.fi/tsoha/shops/shop/1?edit=true
* http://valkorol.users.cs.helsinki.fi/tsoha/groups/group/1?edit=true
* http://valkorol.users.cs.helsinki.fi/tsoha/groups/group/1?edit=true
* http://valkorol.users.cs.helsinki.fi/tsoha/products/product/1?add=true
* http://valkorol.users.cs.helsinki.fi/tsoha/shoppinglists/shoppinglist/1?add=true
* http://valkorol.users.cs.helsinki.fi/tsoha/shops/shop/1?add=true
* http://valkorol.users.cs.helsinki.fi/tsoha/groups/group/1?add=true
* http://valkorol.users.cs.helsinki.fi/tsoha/profile
* https://github.com/korolainen/Tsoha-Bootstrap/blob/master/doc/dokumentaatio.pdf
* https://github.com/korolainen/Tsoha-Bootstrap

## Työn aihe

Kauppakassin hintavertailu

Ruokakaupoilla on keskenään suuria hintaeroja. Joissain kaupassa maito on halvempaa kuin toisessa, kun taas toisessa kaupassa leipä saattaa olla halvempaa. Monesti kaupat ovat vierekkäin ja metropolialueella asuvilla on useampi eri lähikauppa, jossa on eri hinnat vastaaville tuotteille. Kun esimerkiksi maito loppuu, niin mistä kaupasta sitä kannattikaan hakea lisää? Työ matkalla saattaa sijaita jokin marketti, jota ei ole kotikulmilla. Mitäs tuotteita sieltä kotiin tullessa kannattaisikaan ostaa? Yksittäisen tuotteen kohdalla saattaa muistaa missä se on halvimmillaan, mutta koko ostoskorin sisällön hintatietoa voi olla vaikeampi muistaa ilman apuvälinettä. 


Toimintoja:
* Rekisteröityminen
* Kirjautuminen
* Kauppojen lisäys, muokkaus ja poisto
* Tuotteeiden lisäys, muokkaus ja poisto
* Hintatietojen lisäys, muokkaus ja poisto
* Hintatietojen vertailu eri kauppojen kesken
* Ostoslistojen lisäys, muokkaus ja poisto
* Ostoslistojen näkyvyyden salliminen muille saman ruokakunnan jäsenille


<a rel="license" href="http://creativecommons.org/licenses/by-nc/4.0/"><img alt="Creative Commons -lisenssi" style="border-width:0" src="https://i.creativecommons.org/l/by-nc/4.0/80x15.png" /></a><br /><span xmlns:dct="http://purl.org/dc/terms/" property="dct:title">Kauppavertailu</span>, jonka tekijä on <a xmlns:cc="http://creativecommons.org/ns#" href="http://valkorol.users.cs.helsinki.fi/tsoha/" property="cc:attributionName" rel="cc:attributionURL">Valtteri Korolainen</a>, on lisensoitu <a rel="license" href="http://creativecommons.org/licenses/by-nc/4.0/">Creative Commons Nimeä-EiKaupallinen 4.0 Kansainvälinen -lisenssillä</a>.<br />Perustuu teokseen osoitteessa <a xmlns:dct="http://purl.org/dc/terms/" href="http://valkorol.users.cs.helsinki.fi/tsoha/" rel="dct:source">http://valkorol.users.cs.helsinki.fi/tsoha/</a>.