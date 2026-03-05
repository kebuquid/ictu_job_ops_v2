<?= $this->extend('section_heads/layout') ?>

<?= $this->section('pageTitle') ?>Section Employees<?= $this->endSection() ?>
<?= $this->section('pageSubtitle') ?>Staff & Technicians<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="p-8 space-y-6">
  <div class="fade-in delay-1 bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-blue-100/50 shadow-lg">
    <h3 class="font-bold text-gray-900 text-lg mb-5">Section Employees</h3>
    <div class="overflow-x-auto">
      <table id="employeesTable" class="w-full text-sm">
        <thead>
          <tr class="text-left text-xs text-gray-400 uppercase tracking-wider border-b border-gray-100">
            <th class="pb-3 pr-4">Name</th>
            <th class="pb-3 pr-4">Email</th>
            <th class="pb-3 pr-4">Role</th>
            <th class="pb-3">Section</th>
          </tr>
        </thead>
        <tbody>
          <?php if(!empty($employees)): ?>
            <?php foreach($employees as $emp): ?>
            <tr class="ticket-row border-b border-gray-50">
              <td class="py-3 pr-4">
                <div class="flex items-center gap-3">
                  <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-xs"><?= $emp['initials'] ?></div>
                  <span class="font-semibold text-gray-800"><?= esc($emp['name']) ?></span>
                </div>
              </td>
              <td class="py-3 pr-4 text-gray-600"><?= esc($emp['email']) ?></td>
              <td class="py-3 pr-4">
                <span class="text-xs font-bold px-2 py-1 rounded-full bg-<?= $emp['role_color'] ?>-100 text-<?= $emp['role_color'] ?>-700"><?= $emp['role'] ?></span>
              </td>
              <td class="py-3 text-gray-600"><?= esc($emp['acronym'] ?? $emp['section_name'] ?? 'N/A') ?></td>
            </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="4" class="py-8 text-center text-gray-400">No employees found</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<script>
$(document).ready(function() {
  if($.fn.dataTable && $('#employeesTable tbody tr').length > 0 && !$('#employeesTable tbody tr td[colspan]').length) {
    $('#employeesTable').DataTable({ pageLength: 15 });
  }
});
</script>
<?= $this->endSection() ?>
