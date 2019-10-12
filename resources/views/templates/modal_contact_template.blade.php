<div class="modal fade" id="contactModal" tabindex="-1" role="dialog" aria-labelledby="contactModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Envoyer un message</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="contactForm" method="POST"
          action={{ route('group.contactModal', ['groupName' => $group->name, 'userId' => auth()->user()->id]) }}
          enctype="multipart/form-data">
          @csrf
          <div class="form-group">
            @if (count($receiverIds) > 1)
            <label for="receivers">Destinataires:</label>
            <select class="form-control" name="receiverId" id="receivers">
              <option value="{{ $receiverIds }}">Tous</option>
              @foreach ($receiverIds as $id)
              @php
              $receiverUser = App\User::find($id);
              @endphp
              <option value={{ $receiverUser }}>{{ $receiverUser->name }}</option>
              @endforeach
            </select>
            @else
            <input form="contactForm" type="text" name="receiverId" value={{ $receiverIds[0] }} hidden>
            @endif
          </div>
          <div class="form-group">
            <label for="subject">Sujet</label>
            <input form="contactForm" type="text" class="form-control" name="subject" aria-describedby="helpId" placeholder="" required>
            <small id="helpId" class="form-text text-muted"></small>
          </div>
          <div class="form-group">
            <label for="message">Message</label>
            <textarea form="contactForm" class="form-control" name="message" aria-describedby="helpId" required></textarea>
            <small id="helpId" class="form-text text-muted"></small>
          </div>
        </form>
        <p>Ce formulaire permet de contacter un membre du groupe. Votre e-mail ne sera pas visible par celui-ci, il ne pourra
          pas vous répondre directement.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Annuler</button>
        <button type="submit" form="contactForm" class="btn btn-primary btn-sm">Envoyer</button>
      </div>
    </div>
  </div>
</div>