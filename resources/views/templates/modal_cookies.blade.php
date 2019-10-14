<div class="modal fade" id="cookieModal" tabindex="-1" role="dialog" aria-labelledby="cookieModalLabel"
  aria-hidden="true" data-backdrop="static" data-show="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="cookieModalLabel">Cookies</h5>
      </div>
      <div class="modal-body">
        <p>Les données enregistrées sur votre ordinateur (cookies) lors de l'utilisation du site servent au bon
          fonctionnement de celui-ci. En aucun cas
          nous n'utilisons vos données à des fins de traçage ou mercantiles. Les données de ces cookies sont cryptées et
          signées pour limiter le risque de piratage de vos données.</p>
        <p>Aucun cookie de sites tiers n'est utilisé.</p>
        <p>Néanmoins, si vous décidez de bloquer l'enregistrement de ces cookies sur votre ordinateur, vous risquez de rencontrer des problèmes lors de l'utilisation de l'application.</p>
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