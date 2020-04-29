<?php

if ($_SERVER["REQUEST_METHOD"] === 'POST') {

  $userData = ["username" => $_ENV['MAGENTO_ADMIN_USERNAME'], "password" => $_ENV['MAGENTO_ADMIN_PASSWORD']];

  $ch = curl_init("http://web:80/rest/V1/integration/admin/token");
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($userData));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json", "Accept: application/json"]);

  $token = json_decode(curl_exec($ch));
  curl_close($ch);

  if (filter_has_var(INPUT_POST, 'name')) {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
  }

  if (filter_has_var(INPUT_POST, 'sku')) {
    $sku = filter_var($_POST['sku'], FILTER_SANITIZE_STRING);
  }

  if (filter_has_var(INPUT_POST, 'price')) {
    $price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_FLOAT);
  }

  if (filter_has_var(INPUT_POST, 'status')) {
    $status = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
  }

  if (filter_has_var(INPUT_POST, 'visibility')) {
    $visibility = filter_var($_POST['visibility'], FILTER_SANITIZE_NUMBER_INT);
  }

  if (filter_has_var(INPUT_POST, 'weight')) {
    $weight = filter_var($_POST['weight'], FILTER_SANITIZE_NUMBER_FLOAT);
  }

  $data = [
    "product" => [
      "sku" => $sku,
      "name" => $name,
      "attribute_set_id" => 9,
      "price" => $price,
      "status" => $status,
      "visibility" => $visibility,
      "type_id" => "simple",
      "weight" => $weight,
      "extension_attributes" => [
        "category_links" => []
      ],
      "custom_attributes" => json_decode($_POST['customAttributes'])
    ]
  ];

  $ch = curl_init("http://web:80/rest/default/V1/products");
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json", "Authorization: Bearer " . $token]);

  curl_exec($ch);
  curl_close($ch);
  exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Uusi tuote</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <div id="app">
    <div class="container">
      <form class="form-horizontal mx-auto my-auto" method="post" @submit.prevent="submit">
        <h1>Uusi tuote</h1>
        <div class="alert alert-success hide" id="product-alert" role="alert" v-if="showProductAlert">
          Tuote lisätty onnistuneesti!
        </div>
        <div class="form-group">
          <label for="name">Nimi</label>
          <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
          <label for="sku">SKU</label>
          <input type="text" class="form-control" id="sku" name="sku" required>
        </div>
        <div class="form-group">
          <label for="price">Hinta</label>
          <div class="input-group">
            <input type="number" class="form-control" id="price" name="price" min="0" step="0.1" aria-describedby="basic-addon1" required>
            <div class="input-group-append">
              <span class="input-group-text" id="basic-addon1">€</span>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label for="status">Tila</label>
          <select id="status" name="status" class="form-control">
            <option value="1">Saatavilla</option>
            <option value="0">Ei saatavilla</option>
          </select>
        </div>
        <div class="form-group">
          <label for="visibility">Näkyvyys</label>
          <select id="visibility" name="visibility" class="form-control">
            <option value="4">Katalogi, etsintä</option>
            <option value="3">Etsintä</option>
            <option value="2">Katalogi</option>
            <option value="1">Älä näytä yksittäisenä</option>
          </select>
        </div>
        <div class="form-group">
          <label for="weight">Paino</label>
          <div class="input-group">
            <input type="number" class="form-control" id="weight" name="weight" min="0" step="0.1" required>
            <div class="input-group-append">
              <span class="input-group-text">KG</span>
            </div>
          </div>
        </div>
        <h3>Mukautetut attribuutit</h3>
        <div class="row align-items-center">
          <div class="col-auto my-1">
            <label for="custom-attribute-name">Nimi</label>
            <div class="input-group">
              <input type="text" class="form-control" id="custom-attribute-name" v-model="newCustomAttribute['attribute_code']">
            </div>
          </div>
          <div class="col-auto my-1">
            <label for="custom-attribute-value">Arvo</label>
            <div class="input-group">
              <input type="text" class="form-control" id="custom-attribute-value" v-model="newCustomAttribute['value']">
            </div>
          </div>
          <div class="col-auto my-1">
            <button type="button" class="btn btn-primary" @click="addAttribute">Lisää</button>
          </div>
        </div>
        <table class="table my-3">
          <thead>
            <tr>
              <th scope="col">Nimi</th>
              <th scope="col">Arvo</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in customAttributes">
              <th scope="row">{{ item["attribute_code"] }}</th>
              <td>{{ item.value }}</td>
            </tr>
          </tbody>
        </table>
        <button type="submit" class="btn btn-primary">Lähetä</button>
      </form>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/vue@2.6.11/dist/vue.min.js" integrity="sha256-ngFW3UnAN0Tnm76mDuu7uUtYEcG3G5H1+zioJw3t+68=" crossorigin="anonymous"></script>
  <script src="script.js"></script>
</body>

</html>