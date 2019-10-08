<div class="modal fade" id="cookieModal" tabindex="-1" role="dialog" aria-labelledby="cookieModalLabel" aria-hidden="true"
  data-backdrop="static" data-show="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="cookieModalLabel">Cookies</h5>
      </div>
      <div class="modal-body">
        <p>Les données enregistrées sur votre ordinateur (cookies) servent à vérifier votre identifiant lorque vous vous
          connectez au site. Les données de ces cookies sont cryptées et signées.</p>
        <p>Aucun cookie de sites tiers n'est utilisé.</p>
        <p>Néanmoins, si vous ne souhaitez pas autoriser l'enregistrement de cookies venant de "Groups" sur votre ordinateur, vous pouvez le
          faire en bloquant ceux-ci dans les préférences de vie privée de votre navigateur</p>
      </div>
      <div class="modal-footer">
        <a role="button" href={{ route('cookies') }} class="btn btn-primary">J'ai compris</a>
      </div>
    </div>
  </div>
</div>
<script async defer>
  window.addEventListener('load', () => {
    $('#cookieModal').modal('show');
  })
</script>