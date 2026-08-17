<div class="main-container">
  <div class="xs-pd-20-10 pd-ltr-20">
    <div class="page-header">
      <div>
        <h1 class="page-title"><?= $locale['books_literatures'] ?></h1>
        <nav class="lt-bc"><a href="<?= base_url() ?>">Dashboard</a><span>/</span><span><?= $locale['books_literatures'] ?></span></nav>
      </div>
      <a href="<?= base_url('newBook') ?>" class="btn btn-primary lt-cta"><i class="dw dw-add"></i><?= $locale['new_book'] ?></a>
    </div>
    <?php if(session()->getFlashdata('success')):?><div class="lt-alert lt-success"><i class="dw dw-check-circle-2"></i><?=esc(session()->getFlashdata('success'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <?php if(session()->getFlashdata('error')):?><div class="lt-alert lt-danger"><i class="dw dw-close-circle-1"></i><?=esc(session()->getFlashdata('error'))?><button class="lt-x" onclick="this.parentElement.remove()">&times;</button></div><?php endif;?>
    <div class="card-box" style="padding:0;overflow:hidden;">
      <div class="lt-head"><h3 class="lt-htitle">Books &amp; Literature</h3><p class="lt-hsub">Church e-books and publications</p></div>
      <div style="padding:16px 22px 22px;overflow-x:auto;">
        <table id="books_table" class="table nowrap" style="width:100%;">
          <thead><tr>
            <th>#</th><th>Cover</th><th><?=$locale['title']?></th><th><?=$locale['description']?></th><th><?=$locale['book_pricing']?></th><th style="width:90px;"><?=$locale['action']?></th>
          </tr></thead>
          <tbody>
            <?php $c=1; foreach($books as $r): ?>
            <tr>
              <td class="text-muted"><?=$c?></td>
              <td><img src="<?=esc($r->thumbnail)?>" style="width:44px;height:56px;object-fit:cover;border-radius:6px;box-shadow:0 1px 4px rgba(0,0,0,.12);" alt=""></td>
              <td style="font-weight:600;color:var(--t1);"><?=esc($r->title)?></td>
              <td style="color:var(--t2);font-size:.82rem;max-width:240px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?=esc($r->description)?></td>
              <td>
                <?php if(!empty($r->is_for_sale)): ?>
                  <span class="lt-sale-badge"><?=esc($r->currency ?? 'USD')?> <?=number_format((float)($r->price ?? 0), 2)?></span>
                <?php else: ?>
                  <span class="lt-free-badge"><?=$locale['book_free']?></span>
                <?php endif; ?>
              </td>
              <td>
                <div style="display:flex;gap:5px;">
                  <a href="<?=base_url('editBook/'.$r->id)?>" class="lt-ab lt-edit" title="Edit"><i class="dw dw-edit-2"></i></a>
                  <a href="javascript:void(0)" class="lt-ab lt-del" title="Delete" onclick="ltSDelConfirm('book',<?=$r->id?>)"><i class="dw dw-trash"></i></a>
                </div>
              </td>
            </tr>
            <?php $c++; endforeach; ?>
          </tbody>
        </table>
        <?php if(empty($books)):?><div style="padding:40px;text-align:center;color:var(--t3);">No books found</div><?php endif;?>
      </div>
    </div>
  </div>
</div>
<style>
#books_table thead th{font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--t3);border-bottom:2px solid var(--border)!important;border-top:none!important;padding:10px 14px;background:#f8fafc}
#books_table tbody td{padding:10px 14px;border-color:var(--border)!important;font-size:.875rem;vertical-align:middle}
#books_table tbody tr:hover td{background:#f8fafc}#books_table tbody tr:last-child td{border-bottom:none!important}
#books_table_wrapper .dataTables_filter input{border:1.5px solid var(--border);border-radius:8px;padding:6px 12px;font-size:.875rem;outline:none}
#books_table_wrapper .dataTables_filter input:focus{border-color:var(--accent)}
#books_table_wrapper .dataTables_length select{border:1.5px solid var(--border);border-radius:8px;padding:5px 10px;font-size:.875rem}
#books_table_wrapper .dataTables_info{font-size:.8rem;color:var(--t3)}
#books_table_wrapper .paginate_button{border-radius:7px!important;font-size:.82rem;font-weight:600}
#books_table_wrapper .paginate_button.current,#books_table_wrapper .paginate_button.current:hover{background:var(--accent)!important;border-color:var(--accent)!important;color:#fff!important}
.lt-sale-badge{display:inline-block;padding:3px 9px;border-radius:20px;font-size:.75rem;font-weight:700;background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0;white-space:nowrap;}
.lt-free-badge{display:inline-block;padding:3px 9px;border-radius:20px;font-size:.75rem;font-weight:700;background:#f1f5f9;color:#64748b;border:1px solid #e2e8f0;}
</style>
<script>
$(document).ready(function(){
  if($.fn.DataTable.isDataTable('#books_table'))$('#books_table').DataTable().destroy();
  $('#books_table').DataTable({pageLength:15,dom:"<'row mb-2'<'col-sm-6'l><'col-sm-6 text-right'f>>t<'row mt-2'<'col-sm-6'i><'col-sm-6 text-right'p>>",
    language:{search:'',searchPlaceholder:'Search books…',info:'Showing _START_–_END_ of _TOTAL_',paginate:{previous:'‹',next:'›'}},
    columnDefs:[{targets:0,width:'50px',orderable:false},{targets:5,orderable:false}]});
});
function ltSDelConfirm(type,id){swal({title:'Delete?',text:'This cannot be undone.',type:'warning',showCancelButton:true,confirmButtonColor:'#ef4444',confirmButtonText:'Yes, delete'},function(){var u={inbox:'deleteInbox',message:'deleteMessage',book:'deleteBook',branch:'deleteBranch',events:'deleteEvent',groups:'deleteGroup',prayer:'deletePrayer',testimony:'deleteTestimony'};document.location.href=baseURL+'/'+(u[type]||'delete')+'/'+id;});}
</script>
