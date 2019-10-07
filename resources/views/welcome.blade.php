<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">

  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
    integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

  <script src="{{ asset('js/app.js') }}" defer></script>

</head>

<body class="welcome">
  <div class="flex-center position-ref full-height">
    @if (!$cookiesAccept)
    @include('templates/modal_cookies')
    @endif
    <div class="content">
      <div class="title m-b-md">
        {{ config('app.name')}}
        {{$cookiesAccept}}
      </div>
      <h1 class="mb-4">Plateforme de discussion qui respecte la vie privée</h1>
      <div class="links d-flex justify-content-center flex-wrap">
        <div class="flex-fill mb-3">
          <a href="{{ route('login') }}">S'identifier</a>
        </div>
        <div class="flex-fill">
          <a href="{{ route('register') }}">Créer un compte</a>
        </div>
      </div>
      <div class="d-flex justify-content-center mt-5">
        <button type="button" class="btn" data-toggle="modal" data-target="#helpModal">
          <i id="help" class="far fa-question-circle fa-3x"></i>
        </button>
      </div>
    </div>
  </div>
  <div class="modal fade" id="helpModal" tabindex="-1" role="dialog" aria-labelledby="aide" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <p class="modal-title h5" id="aide">A propos de Groups *** En test ***</p>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p class="h5">Qu'est-ce "Groups"?</p>
          <p>"Groups" est une plateforme de groupe de discussions privées respectueuse des données personnelles et du
            droit à l'oubli.
          </p>
          <p class="h5">Comment ça marche?</p>
          <p>
            <ul>
              <li>En créant un compte sur le site, vous avez la possibilité de rejoindre ou de créer un groupe de
                discussion.</li>
              <li>Le créateur d'un groupe dispose des droits d'aministration de ce groupe. C'est à dire qu'il a la
                possibilité de supprimer des articles et des commentaires. Il peut aussi désigner un administrateur
                parmi
                les membre du groupe. L'administrateur du groupe a aussi la faculté d'envoyer des avertissements et
                de radier un membre du groupe.</li>
              <li>Quand un membre quitte le groupe, tous ces articles et commentaires sont aussi supprimés
                définitivement.</li>
            </ul>
          </p>
          <p class="h5">Respect des données personnelles:</p>
          <p>
            <ul>
              <li>"Groups" ne donne, n'échange, ni ne vend les données des utilisateurs inscrits. Les données sont
                stockées via un hébergeur français - gandi - sur ses serveurs.</li>
              <li>"Groups" n'utilise pas d'outils de fournisseurs tiers captant des données comme par exemple des outils
                d'analyse Google ou de lien vers Facebook.</li>
              <li>Vous pouvez connaître à tout moment le contenu des données vous concernant en en faisant la demande.
              </li>
            </ul>
          </p>
          <p class="h5">Le droit à l'oubli:</p>
          <p>
            <ul>
              <li>La suppression d'un compte entraine automatiquement la suppression définitive de toutes les données
                liées à ce compte (données du compte, articles et commentaires).</li>
              <li>Aucune donnée d'un compte supprimé n'est conservée sur les serveurs.</li>
            </ul>
          </p>
          <p class="h5"><i class="fas fa-exclamation-triangle"></i> Version de test:</p>
          <p>
            La plateforme proposée est actuellement en test, il peut y avoir des disfonctionnements. Merci de rapporter
            tout problème sur le group "Groups".
          </p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
        </div>
      </div>
    </div>
  </div>
</body>

</html>