<div class="modal fade" id="modal-confirm" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" style="margin-left: 7%; font-weight: bold;">Apakah Anda Ingin Menghapus data ini ?</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="modal-btn-si" onclick="goSubmitDelete()">Ya</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close" onclick="goBatal()"><span aria-hidden="true">Tidak</span></button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  function CheckDelete(url = ""){
    $("input[name='_method']").val("DELETE");
    $("#form-select").attr("method", "POST");
    $("#form-select").attr("action", url);
    $("#modal-confirm").modal('show');
  }
  
  function goSubmitDelete() {
    $("#form-select").submit();
  }
  
  function goFilter(){
    $("input[name='_method']").val("GET");
    $("#form-select").attr("method", "GET");
    $("#form-select").submit();
  }
  
  function goBatal() {
    $("input[name='_method']").val("GET");
    $("#form-select").attr("method", "GET");
    $("#form-select").attr("action", "{{ url(Request::segment(1)) }}/filter");
  }
  
</script>