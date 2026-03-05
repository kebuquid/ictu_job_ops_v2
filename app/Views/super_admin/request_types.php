<?= $this->extend('super_admin/layout') ?>

<?= $this->section('content') ?>
<div class="p-8 space-y-6">

  <?php if (session()->getFlashdata('success')): ?>
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm"><?= session()->getFlashdata('success') ?></div>
  <?php endif; ?>
  <?php if (session()->getFlashdata('error')): ?>
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm"><?= session()->getFlashdata('error') ?></div>
  <?php endif; ?>

  <div class="fade-in delay-1 bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-blue-100/50 shadow-lg">
    <div class="flex items-center justify-between mb-5">
      <h3 class="font-bold text-gray-900 text-lg">Request Types</h3>
      <a href="<?= base_url('super-admin/request-types/add') ?>"
         class="px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-700 text-white font-semibold text-sm rounded-xl shadow hover:shadow-lg transition-all">+ Add Request Type</a>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full text-sm" id="requestTypesTable">
        <thead>
          <tr class="text-left text-xs text-gray-400 uppercase tracking-wider border-b border-gray-100">
            <th class="pb-3 pr-4">#</th>
            <th class="pb-3 pr-4">Request Type Name</th>
            <th class="pb-3 pr-4">Section</th>
            <th class="pb-3">Actions</th>
          </tr>
        </thead>
        <tbody>
            <?php foreach ($requestTypes as $item): ?>
              <tr class="ticket-row border-b border-gray-50">
                <td class="py-3 pr-4">
                  <span class="text-gray-400 font-semibold text-xs"><?= esc($item['request_type_id']) ?></span>
                </td>
                <td class="py-3 pr-4">
                  <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-400 to-violet-600 text-white text-sm flex items-center justify-center font-bold shadow-sm shrink-0">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    </div>
                    <span class="text-gray-800 font-semibold text-sm"><?= esc($item['request_type_name']) ?></span>
                  </div>
                </td>
                <td class="py-3 pr-4">
                  <span class="inline-block px-2.5 py-1 bg-amber-100 text-amber-700 text-xs font-bold rounded-full"><?= esc($item['acronym'] ?? '—') ?></span>
                </td>
                <td class="py-3">
                  <div class="flex items-center gap-2">
                    <a href="<?= base_url('super-admin/request-types/edit/' . $item['request_type_id']) ?>"
                       class="inline-flex items-center gap-1 bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors">
                      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                      View / Edit
                    </a>
                    <button type="button"
                            onclick="confirmDelete(<?= $item['request_type_id'] ?>, '<?= esc($item['request_type_name'], 'js') ?>')"
                            class="inline-flex items-center gap-1 bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors">
                      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                      Delete
                    </button>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 backdrop-blur-sm">
  <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-sm mx-4 transform transition-all">
    <div class="flex items-center gap-3 mb-4">
      <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center">
        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
      </div>
      <div>
        <h4 class="font-bold text-gray-900">Delete Request Type</h4>
        <p class="text-xs text-gray-500">This action cannot be undone.</p>
      </div>
    </div>
    <p class="text-sm text-gray-600 mb-5">Are you sure you want to delete <strong id="deleteRequestTypeName" class="text-gray-900"></strong>?</p>
    <form id="deleteForm" method="POST" action="">
      <?= csrf_field() ?>
      <input type="hidden" name="_method" value="DELETE">
      <div class="flex gap-3">
        <button type="button" onclick="closeDeleteModal()" class="flex-1 px-4 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-sm font-semibold hover:bg-gray-50 transition-colors">Cancel</button>
        <button type="submit" class="flex-1 px-4 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-sm font-semibold transition-colors">Delete</button>
      </div>
    </form>
  </div>
</div>

<script>
$(document).ready(function () {
  if($.fn.dataTable) {
    $('#requestTypesTable').DataTable({
      pageLength: 15,
      order: [[0, 'desc']],
      language: {
        emptyTable: 'No request types found. Add one to get started.'
      }
    });
  }
});

function confirmDelete(id, name) {
  $('#deleteRequestTypeName').text(name);
  $('#deleteForm').attr('action', '<?= base_url("super-admin/request-types/delete") ?>/' + id);
  $('#deleteModal').removeClass('hidden').addClass('flex');
}
function closeDeleteModal() {
  $('#deleteModal').removeClass('flex').addClass('hidden');
}
$('#deleteModal').on('click', function (e) {
  if (e.target === this) closeDeleteModal();
});
</script>
<?= $this->endSection() ?>
