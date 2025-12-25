@extends('layouts.app')

@section('content')
<div class="container">

    <h2>Barangay Management</h2>

    <!-- Municipality Dropdown -->
    <label for="municipality">Select Municipality:</label>
    <select id="municipality" class="form-control">
        <option value="">-- Select Municipality --</option>
        @foreach($municipalities as $municipality)
            <option value="{{ $municipality->id }}">{{ $municipality->name }}</option>
        @endforeach
    </select>

    <br>

    <!-- Barangay Table -->
    <table class="table table-bordered" id="barangay-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Chairperson</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Barangays will load here dynamically later -->
        </tbody>
    </table>
<!-- Dito sa ilalim ng table -->
<!-- Add Barangay Modal -->
<div class="modal fade" id="addBarangayModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="add-barangay-form" method="POST" action="{{ route('abc.barangays.store') }}">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add Barangay</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="municipality_id" id="form-municipality-id">
          <div class="mb-3">
            <label>Barangay Name</label>
            <input type="text" name="name" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Add Barangay</button>
        </div>
      </div>
    </form>
  </div>
</div><!-- Edit Barangay Modal -->
<div class="modal fade" id="editBarangayModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="edit-barangay-form" method="POST">
      @csrf
      @method('PUT')
      <input type="hidden" name="id" id="edit-barangay-id">
      <input type="hidden" name="municipality_id" id="edit-municipality-id">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Barangay</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Barangay Name</label>
            <input type="text" name="name" id="edit-barangay-name" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-warning">Update Barangay</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- JavaScript: Load barangays dynamically -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){

    // Update hidden input sa Add form
    $('#municipality').change(function(){
        var muniId = $(this).val();
        $('#form-municipality-id').val(muniId);

        if(muniId === '') {
            $('#barangay-table tbody').html('');
            return;
        }

        // AJAX request: load barangays
        $.ajax({
            url: '/abc/barangays-by-municipality/' + muniId,
            type: 'GET',
            success: function(data){
                var rows = '';
                data.forEach(function(barangay){
                    rows += '<tr>';
                    rows += '<td>'+ barangay.name +'</td>';
                    rows += '<td>'+ (barangay.chairperson || '') +'</td>';
                    rows += '<td><button class="btn btn-sm btn-warning editBarangayBtn" data-id="'+ barangay.id +'" data-name="'+ barangay.name +'">Edit</button></td>';
                    rows += '</tr>';
                });
                $('#barangay-table tbody').html(rows);
            },
            error: function(){
                alert('Failed to load barangays.');
            }
        });
    });

});
// Open Edit modal at populate fields
$(document).on('click', '.editBarangayBtn', function(){
    var id = $(this).data('id');
    var name = $(this).data('name');
    var municipalityId = $('#municipality').val();

    $('#edit-barangay-id').val(id);
    $('#edit-barangay-name').val(name);
    $('#edit-municipality-id').val(municipalityId);

    $('#editBarangayModal').modal('show');
});

// AJAX submit Edit form
$('#edit-barangay-form').submit(function(e){
    e.preventDefault();
    var id = $('#edit-barangay-id').val();
    var formData = $(this).serialize();

    $.ajax({
        url: '/abc/barangays/' + id,
        type: 'PUT',
        data: formData,
        success: function(response){
            alert('Barangay updated successfully!');
            $('#editBarangayModal').modal('hide');
            $('#municipality').trigger('change'); // reload barangay table
        },
        error: function(xhr){
            alert('Failed to update barangay.');
        }
    });
});

</script>

</div>
@endsection
