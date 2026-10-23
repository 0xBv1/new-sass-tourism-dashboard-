<a href="javascript:;"
   class="show-message"
   title="Show More Details"
   data-message="{{ $contactRequest->message }}"
   data-name="{{ $contactRequest->name }}"
   data-bs-toggle="modal" data-bs-target="#showMessageModal">
    <i class="fa fa-eye"></i>
</a>


<a href="javascript:;"
   class="mark-as-spam"
   title="Mark as spam"
   data-email="{{ $contactRequest->email }}"
   data-bs-toggle="modal" data-bs-target="#markAsSpamModal">
    <i class="fa fa-ban"></i>
</a>
