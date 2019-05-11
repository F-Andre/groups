<div class="modal fade" id="deleteAccount" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title text-center">Supprimer un compte</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p class="h4 text-center my-3"><b>ATTENTION!</b><br>Le compte de cet utilisateur ainsi que ses articles et commentaires seront
          supprimés définitivement!<br></p>
        <form method="POST" action="{{ route('admin.destroy', ['id' => $user->id]) }}">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger float-right">Continuer</button>
        </form>
        <button type="button" class="btn btn-success" data-dismiss="modal">Annuler</button>
      </div>
    </div>
  </div>
</div>
