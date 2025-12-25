<form action="{{ route('secretary.reports.store') }}" method="POST">
    @csrf

    <label>Petsa ng Koleksyon:</label>
    <input type="date" name="date_collected" required>

    <label>Biodegradable:</label>
    <input type="number" name="biodegradable" value="0" min="0">

    <label>Recyclable:</label>
    <input type="number" name="recyclable" value="0" min="0"> <!-- ✅ ADD THIS -->

    <label>Residual (Recyclable):</label>
    <input type="number" name="residual_recyclable" value="0" min="0">

    <label>Residual (Disposal):</label>
    <input type="number" name="residual_disposal" value="0" min="0">

    <label>Special:</label>
    <input type="number" name="special" value="0" min="0">

    <label>Remarks:</label>
    <textarea name="remarks"></textarea>

    <button type="submit">Isave</button>
</form>
