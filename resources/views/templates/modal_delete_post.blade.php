<div class="modal fade" id="deletePost" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title">Supprimer un article</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p class="h3 text-center my-3">ATTENTION!<br>La suppression d'un article est définitive!<br></p>
        <form method="POST" action="{{ route('posts.destroy', ['id' => $post->id, 'group' => $groupName]) }}">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger float-right">Continuer</button>
        </form>
        <button type="button" class="btn btn-success float-left" data-dismiss="modal">Annuler</button>
      </div>
    </div>
  </div>
</div>
