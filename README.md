# Pour le 04/11

- Ajouter l'utilisation du layout dans le fichier home.html.twig
- Ecrire un validateur capable de valider des entités. Ce genre de code devrait fonctionner : 

```php
<?php
/**
 * Class User with these properties : username, firstName, email
 */
$user = new User();
$user->setFirstName('John');

$errors = $validator->validate($user);
```
