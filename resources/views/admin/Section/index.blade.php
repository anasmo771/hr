@extends('admin.layout.master')

@section('title')
  <title>الهيكل التنظيمي و ادارة الوظائف</title>
@endsection

@section('content')
@php
  use Illuminate\Support\Facades\Route;

  // نفس المنطق كما هو
  $roots     = $roots ?? ($all ?? collect());
  $staffings = $staffings ?? collect();

  $hasUFStore  = Route::has('unitStaffings.store');
  $hasUFUpdate = Route::has('unitStaffings.update');
  $hasUFDest   = Route::has('unitStaffings.destroy');
  $canManageUF = $hasUFStore && $hasUFUpdate && $hasUFDest;

  $sectionDeleteRouteName = Route::has('subSection.destroy') ? 'subSection.destroy' : (Route::has('deletesub') ? 'deletesub' : null);

  if (!function_exists('renderUnitNode')) {
    function renderUnitNode($unit, $canManageUF, $sectionDeleteRouteName) {
      $children  = $unit->sub ?? collect();
      $positions = $unit->unitPositions ?? collect();
      $hasKids   = $children && $children->count() > 0;
      $quotaSum  = (int) $positions->sum('quota');

      echo '<li class="unit-node mb-3">';
        echo '<div class="unit-row d-flex flex-row-reverse align-items-start justify-content-between" dir="rtl">';

            // الأزرار (يسار)
            echo '<div class="d-flex flex-wrap gap-2 actions">';

            // إدارة الوظائف
            if ($canManageUF) {
                echo '<button title="إضافة موضع ضمن الوظائف" type="button" class="btn btn-outline-dark btn-eq"
                        data-bs-toggle="modal"
                        data-bs-target="#posModal"
                        data-unit-id="'.$unit->id.'"
                        data-unit-name="'.e($unit->name).'"><i class="fa-solid fa-user-plus"></i></button>';
            } else {
                echo '<button title="إضافة موضع ضمن الوظائف" type="button" class="btn btn-outline-dark btn-eq" disabled
                        title="فعّل راوت unitStaffings.*">إدارة الوظائف</button>';
            }

            // إضافة فرع
            echo '<button title="إضافة قسم تابع" type="button" class="btn btn-outline-success btn-eq"
                    data-bs-toggle="modal"
                    data-bs-target="#addChildModal"
                    data-parent-id="'.$unit->id.'"
                    data-parent-name="'.e($unit->name).'">
                    <i class="fa-solid fa-computer"></i>
                    </button>';

            // تعديل
            if (Route::has('subSection.edit')) {
                echo '<a href="'.route('subSection.edit', $unit->id).'"
                        class="btn btn-outline-primary btn-eq">
                        <i class="fa-solid fa-pen"></i>
                    </a>';
            }

            // شارة الوظيفة
            echo '<span class="badge bg-secondary ms-2">الوظيفة: '.$quotaSum.'</span>';

            // حذف (صغير ومُحاذى رأسيًا) – في النهاية ليظهر على يمين المجموعة
            if ($sectionDeleteRouteName) {
                $action = $sectionDeleteRouteName === 'subSection.destroy'
                ? route('subSection.destroy', $unit->id)
                : route('deletesub', $unit->id);

                echo '<form action="'.$action.'" method="POST"
                        class="uf-del-form d-inline-flex align-items-center m-0 p-0"
                        onsubmit="return confirm(\'سيتم حذف الوحدة وفروعها. متابعة؟\')">'.
                        csrf_field().method_field('DELETE').'
                        <button class="btn btn-outline-danger uf-mini" title="حذف">
                        <i class="mdi mdi-delete"></i>
                        </button>
                    </form>';
            }

            echo '</div>';


          // عنوان الوحدة (يمين)
          echo '<div class="d-flex gap-2 align-items-start unit-head">';
            if ($hasKids) {
              echo '<button type="button" class="btn btn-sm btn-light toggle-children" title="توسيع/طي"><i class="mdi mdi-chevron-down"></i></button>';
            } else {
              echo '<span class="btn btn-sm btn-light toggle-children disabled"><i class="mdi mdi-dots-horizontal"></i></span>';
            }
            echo '<div class="d-flex flex-column">';
              echo '<div class="fw-semibold unit-name">'.e($unit->name).'</div>';
            echo '</div>';
          echo '</div>';

        echo '</div>';

        // لائحة الوظيفة
        if ($positions->count() > 0) {
          echo '<div class="positions-list mt-2" dir="rtl">';
            echo '<div class="small text-muted mb-1">الوظيفة المحدَّد لهذه الوحدة:</div>';
            echo '<ul class="list-unstyled ms-4 mb-0">';
            foreach ($positions->sortBy('sort_order') as $pos) {
              $title = $pos->title ?: ($pos->staffing->name ?? '—');
              $isMgr = $pos->is_manager ? '<span class="badge badge-lead ms-1">قيادي</span>' : '';
              echo '<li class="d-flex align-items-center justify-content-between mb-1">';
                echo '<div class="d-flex align-items-center gap-2"><i class="mdi mdi-briefcase-outline text-muted"></i> <span>'.e($title).'</span> <span class="badge badge-qty">'.$pos->quota.' مقعد</span> '.$isMgr.'</div>';
                
                if ($canManageUF) {
                    echo '<div class="pos-actions d-inline-flex align-items-center gap-1">';

                        // زر التعديل (أيقونة صغيرة)
                        echo '<button class="btn btn-outline-primary uf-mini edit-pos"
                                data-bs-toggle="modal" data-bs-target="#posEditModal"
                                data-id="'.$pos->id.'"
                                data-unit-id="'.$unit->id.'"
                                data-title="'.e($pos->title).'"
                                data-staffing-id="'.($pos->staffing_id).'"
                                data-quota="'.$pos->quota.'"
                                data-sort="'.$pos->sort_order.'"
                                data-manager="'.($pos->is_manager?1:0).'"
                                data-notes="'.e($pos->notes).'"
                                title="تعديل">
                                <i class="mdi mdi-pencil"></i>
                            </button>';

                        // زر الحذف (اجعل الـ form نفسه inline-flex ليتوسّط رأسيًا)
                        echo '<form action="'.route('unitStaffings.destroy', $pos->id).'"
                                method="POST"
                                class="pos-del-form d-inline-flex align-items-center m-0 p-0"
                                onsubmit="return confirm(\'حذف هذا الموضع من الوظيفة؟\')">
                                '.csrf_field().method_field('DELETE').'
                                <button class="btn btn-outline-danger uf-mini" title="حذف">
                                <i class="mdi mdi-delete"></i>
                                </button>
                            </form>';

                    echo '</div>';
                    }

              echo '</li>';
            }
            echo '</ul>';
          echo '</div>';
        }

        if ($hasKids) {
          echo '<ul class="children mt-2">';
            foreach ($children->sortBy('sort_order') as $child) { renderUnitNode($child, $canManageUF, $sectionDeleteRouteName); }
          echo '</ul>';
        }
      echo '</li>';
    }
  }
@endphp

<div class="pc-container" dir="rtl">
  <div class="pc-content">
    <div class="page-header">
      <div class="page-block">
        <div class="row align-items-center">
          <div class="col-md-12">
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ route('home') }}">لوحة التحكم</a></li>
              <li class="breadcrumb-item" aria-current="page">الهيكل التنظيمي و ادارة الوظائف</li>
            </ul>
          </div>
          <div class="col-md-12">
            <div class="page-header-title"><h2 class="mb-0">الهيكل التنظيمي و ادارة الوظائف</h2></div>
          </div>
        </div>
      </div>
    </div>

    @include('admin.layout.validation-messages')

    <div class="card shadow-sm border-0 rounded-4">
      <div class="card-body">
        <div class="d-flex flex-wrap gap-2 mb-3">
          @if(Route::has('subSection.create'))
            <a href="{{ route('subSection.create') }}" class="btn btn-primary btn-soft"><i class="mdi mdi-plus"></i> إضافة وحدة إدارية</a>
          @endif
          <button type="button" class="btn btn-outline-secondary btn-soft" id="expandAll">توسيع الكل</button>
          <button type="button" class="btn btn-outline-secondary btn-soft" id="collapseAll">طيّ الكل</button>
        </div>

        @if($roots->count() === 0)
          <div class="text-center text-muted py-5">لا توجد وحدات إدارية بعد.</div>
        @else
          <ul class="org-tree list-unstyled">
            @foreach($roots->sortBy('sort_order') as $root)
              @php renderUnitNode($root, $canManageUF, $sectionDeleteRouteName); @endphp
            @endforeach
          </ul>
        @endif
      </div>
    </div>
  </div>
</div>

{{-- Modal: إضافة فرع --}}
<div class="modal fade" id="addChildModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content rounded-4">
      <form method="POST" action="{{ route('subSectionStore') }}">
        @csrf
        <div class="modal-header border-0">
          <h5 class="modal-title">إضافة قسم تابع</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" dir="rtl">
          <input type="hidden" name="section_id" id="parent_id">
          <div class="mb-2 small text-muted">الوحدة الأم: <span id="parent_name" class="fw-semibold"></span></div>
          <div class="mb-3">
            <label class="form-label">اسم القسم</label>
            <input type="text" class="form-control rounded-3" name="name" required>
          </div>
        </div>
        <div class="modal-footer border-0">
          <button type="button" class="btn btn-light btn-soft" data-bs-dismiss="modal">إلغاء</button>
          <button type="submit" class="btn btn-primary btn-soft">حفظ</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Modal: إضافة موضع ملاك --}}
<div class="modal fade" id="posModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content rounded-4">
    @if($canManageUF)
      <form method="POST" action="{{ route('unitStaffings.store') }}">
        @csrf
        <div class="modal-header border-0">
          <h5 class="modal-title">إضافة وظيفة</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" dir="rtl">
          <input type="hidden" name="unit_id" id="pos_unit_id">
          <div class="mb-2 small text-muted">الوحدة: <span id="pos_unit_name" class="fw-semibold"></span></div>

          <div class="mb-3">
            <label class="form-label">المسمّى الوظيفي</label>
            <select name="staffing_id" class="form-select rounded-3" required @disabled($staffings->count()==0)>
              <option value="" disabled selected>اختر مسمّى</option>
              @foreach($staffings as $sf)
                <option value="{{ $sf->id }}">{{ $sf->name }}</option>
              @endforeach
            </select>
            @if($staffings->count()==0)
              <div class="form-text text-danger">لا توجد مسميات وظيفية بعد — أضفها من قائمة المسمّى الوظيفي”.</div>
            @endif
          </div>

          <div class="row g-2">
            <div class="col-6">
              <label class="form-label">عدد الوظائف</label>
              <input type="number" name="quota" class="form-control rounded-3" value="1" min="1" required>
            </div>
            <div class="col-6">
              <label class="form-label">ترتيب العرض</label>
              <input type="number" name="sort_order" class="form-control rounded-3" value="0" min="0">
            </div>
          </div>

          <div class="form-check mt-2">
            <input class="form-check-input" type="checkbox" id="is_manager" name="is_manager" value="1">
            <label class="form-check-label" for="is_manager">وظيفة قيادية</label>
          </div>

          <div class="mt-3">
            <label class="form-label">عنوان مخصص (اختياري)</label>
            <input type="text" name="title" class="form-control rounded-3" placeholder="مثال: مسؤول كنترول رئيسي">
          </div>

          <div class="mt-3">
            <label class="form-label">ملاحظات (اختياري)</label>
            <textarea name="notes" rows="2" class="form-control rounded-3"></textarea>
          </div>
        </div>
        <div class="modal-footer border-0">
          <button type="button" class="btn btn-light btn-soft" data-bs-dismiss="modal">إلغاء</button>
          <button type="submit" class="btn btn-primary btn-soft">حفظ</button>
        </div>
      </form>
    @else
      <div class="modal-header"><h5 class="modal-title">إدارة الوظائف</h5></div>
      <div class="modal-body"><div class="text-danger">يرجى تفعيل راوت <code>unitStaffings.*</code> أولاً.</div></div>
      <div class="modal-footer"><button type="button" class="btn btn-light btn-soft" data-bs-dismiss="modal">إغلاق</button></div>
    @endif
    </div>
  </div>
</div>

{{-- Modal: تعديل موضع ملاك --}}
<div class="modal fade" id="posEditModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content rounded-4">
    @if($canManageUF)
      <form method="POST" action="{{ route('unitStaffings.update', 0) }}" id="posEditForm">
        @csrf @method('PUT')
        <div class="modal-header border-0">
          <h5 class="modal-title">تعديل موضع ضمن الوظائف</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" dir="rtl">
          <input type="hidden" name="unit_id" id="edit_unit_id">

          <div class="mb-3">
            <label class="form-label">المسمّى الوظيفي</label>
            <select name="staffing_id" id="edit_staffing_id" class="form-select rounded-3" required>
              @foreach($staffings as $sf)
                <option value="{{ $sf->id }}">{{ $sf->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="row g-2">
            <div class="col-6">
              <label class="form-label">عدد الوظائف</label>
              <input type="number" name="quota" id="edit_quota" class="form-control rounded-3" min="1" required>
            </div>
            <div class="col-6">
              <label class="form-label">ترتيب العرض</label>
              <input type="number" name="sort_order" id="edit_sort" class="form-control rounded-3" min="0">
            </div>
          </div>

          <div class="form-check mt-2">
            <input class="form-check-input" type="checkbox" id="edit_is_manager" name="is_manager" value="1">
            <label class="form-check-label" for="edit_is_manager">وظيفة قيادية</label>
          </div>

          <div class="mt-3">
            <label class="form-label">عنوان مخصص (اختياري)</label>
            <input type="text" name="title" id="edit_title" class="form-control rounded-3">
          </div>

          <div class="mt-3">
            <label class="form-label">ملاحظات (اختياري)</label>
            <textarea name="notes" id="edit_notes" rows="2" class="form-control rounded-3"></textarea>
          </div>
        </div>
        <div class="modal-footer border-0">
          <button type="button" class="btn btn-light btn-soft" data-bs-dismiss="modal">إلغاء</button>
          <button type="submit" class="btn btn-primary btn-soft">حفظ</button>
        </div>
      </form>
    @else
      <div class="modal-header"><h5 class="modal-title">إدارة الوظيفة</h5></div>
      <div class="modal-body"><div class="text-danger">يرجى تفعيل راوت <code>unitStaffings.*</code> أولاً.</div></div>
      <div class="modal-footer"><button type="button" class="btn btn-light btn-soft" data-bs-dismiss="modal">إغلاق</button></div>
    @endif
    </div>
  </div>
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function () {
  // نفس السكربت كما هو
  const addModal = document.getElementById('posModal');
  addModal?.addEventListener('show.bs.modal', function (e) {
    const btn = e.relatedTarget; if (!btn) return;
    document.getElementById('pos_unit_id').value = btn.dataset.unitId || '';
    document.getElementById('pos_unit_name').textContent = btn.dataset.unitName || '';
  });

  const editModal = document.getElementById('posEditModal');
  editModal?.addEventListener('show.bs.modal', function (e) {
    const btn = e.relatedTarget; if (!btn) return;
    const form = document.getElementById('posEditForm');
    const base = form.getAttribute('data-base') || form.action;
    form.setAttribute('data-base', base);
    form.action = base.replace(/\/\d+$/, '/' + encodeURIComponent(btn.dataset.id));
    edit_unit_id.value      = btn.dataset.unitId     || '';
    edit_title.value        = btn.dataset.title      || '';
    edit_staffing_id.value  = btn.dataset.staffingId || '';
    edit_quota.value        = btn.dataset.quota      || 1;
    edit_sort.value         = btn.dataset.sort       || 0;
    edit_is_manager.checked = (btn.dataset.manager === '1');
    edit_notes.value        = btn.dataset.notes      || '';
  });

  const addChild = document.getElementById('addChildModal');
  addChild?.addEventListener('show.bs.modal', function (e) {
    const btn = e.relatedTarget; if (!btn) return;
    parent_id.value = btn.dataset.parentId || '';
    parent_name.textContent = btn.dataset.parentName || '';
  });

  document.querySelectorAll('.toggle-children').forEach(btn => {
    btn.addEventListener('click', function () {
      const li = this.closest('li.unit-node');
      const children = li ? li.querySelector(':scope > ul.children') : null;
      if(!children) return;
      children.classList.toggle('show');
      this.innerHTML = children.classList.contains('show')
        ? '<i class="mdi mdi-chevron-down"></i>'
        : '<i class="mdi mdi-chevron-right"></i>';
    });
  });

  document.getElementById('expandAll')?.addEventListener('click', ()=> {
    document.querySelectorAll('ul.children').forEach(c => c.classList.add('show'));
    document.querySelectorAll('.toggle-children').forEach(b => b.innerHTML = '<i class="mdi mdi-chevron-down"></i>');
  });

  document.getElementById('collapseAll')?.addEventListener('click', ()=> {
    document.querySelectorAll('ul.children').forEach(c => c.classList.remove('show'));
    document.querySelectorAll('.toggle-children').forEach(b => b.innerHTML = '<i class="mdi mdi-chevron-right"></i>');
  });
});
</script>

<style>
  /* لوحة ألوان خفيفة ومحايدة */
  :root{
    --clr-primary:#2563eb; /* أزرق */
    --clr-success:#16a34a; /* أخضر */
    --clr-danger:#ef4444;  /* أحمر */
    --clr-ink:#334155;     /* رمادي داكن */
    --clr-border:#e7e9ef;
    --clr-soft:#f8fafc;
  }

  /* كارد رئيسي */
  .card.rounded-4{border-radius:16px}
  .card.rounded-4 .card-body{padding:18px}

  /* صف الوحدة */
  .unit-row{
    padding:.7rem .9rem; background:#fff; border:1px solid var(--clr-border);
    border-radius:14px; box-shadow:0 1px 2px rgba(0,0,0,.03)
  }
  .unit-row:hover{box-shadow:0 6px 16px rgba(0,0,0,.06)}
  .unit-name{font-size:.98rem}

  /* أزرار ناعمة */
  .btn-soft{border-radius:.5rem; padding:.32rem .6rem; font-size:.84rem}
  .btn-xs{--bs-btn-padding-y:.15rem; --bs-btn-padding-x:.35rem; --bs-btn-font-size:.75rem; border-radius:.45rem}

  .org-tree .btn-outline-primary{color:var(--clr-primary);border-color:rgba(37,99,235,.26);background:rgba(37,99,235,.06)}
  .org-tree .btn-outline-primary:hover{color:#fff;background:var(--clr-primary);border-color:var(--clr-primary)}

  .org-tree .btn-outline-success{color:var(--clr-success);border-color:rgba(22,163,74,.26);background:rgba(22,163,74,.06)}
  .org-tree .btn-outline-success:hover{color:#fff;background:var(--clr-success);border-color:var(--clr-success)}

  .org-tree .btn-outline-danger{color:var(--clr-danger);border-color:rgba(239,68,68,.26);background:rgba(239,68,68,.06)}
  .org-tree .btn-outline-danger:hover{color:#fff;background:var(--clr-danger);border-color:var(--clr-danger)}

  .org-tree .btn-outline-dark{color:var(--clr-ink);border-color:rgba(51,65,85,.26);background:rgba(51,65,85,.06)}
  .org-tree .btn-outline-dark:hover{color:#fff;background:var(--clr-ink);border-color:var(--clr-ink)}

  .org-tree .btn-light{background:#fff;border-color:var(--clr-border)}
  .org-tree .btn-light:hover{background:var(--clr-soft)}

  /* شارات */
  .badge-qty{background:#62a0ea;color:#111827;border:1px solid #62a0ea;border-radius:.5rem;padding:.15rem .5rem;font-weight:600}
  .badge-lead{background:#fbab60;color:#7a5b00;border:1px solid #fbab60;border-radius:.5rem;padding:.1rem .45rem;font-weight:600}

  /* شجرة الأبناء */
  ul.children{display:none;margin:.5rem 2.2rem 0 0;padding:0;border-right:2px dashed #f1f5f9}
  ul.children.show{display:block}

  /* تنسيق مجموعة الأزرار */
  .actions .btn i{font-size:16px}
  .toggle-children{width:32px;height:32px;border-radius:.5rem}
  .toggle-children.disabled{opacity:.6}

  /* صف الوحدة: توسيط عمودي */
.unit-row{
    display:flex; flex-direction:row-reverse;
    align-items:center;               /* <-- كان start */
    justify-content:space-between;
    gap:.75rem; padding:.75rem .9rem;
    border:1px solid #eef0f2; border-radius:.75rem; background:#fff;
  }

  /* حاوية الأزرار */
  .unit-actions{
    display:flex; align-items:center; gap:.5rem; flex-wrap:wrap;
  }

  /* أزرار نصية (نفس الارتفاع) */
  .unit-actions .btn-eq{
    height:36px;                      /* ارتفاع موحّد */
    padding:.35rem .8rem;             /* حشوة موحّدة */
    display:inline-flex; align-items:center; justify-content:center;
    line-height:1; border-radius:999px;
  }

  /* زر أيقونة فقط (حذف) بنفس ارتفاع بقية الأزرار */
  .unit-actions .btn-icon{
    width:36px; height:36px; padding:0;
    display:inline-flex; align-items:center; justify-content:center;
    border-radius:999px; line-height:1;
  }
   /* شارات وعدّادات */
  .badge{ display:inline-flex; align-items:center; justify-content:center; }

 /* حاوية مجموعة الأزرار الصغيرة */
.pos-actions{
  display:inline-flex;            /* مهم */
  align-items:center;             /* توسيط رأسي */
  gap:6px;
}

/* اجعل الـ form (زر الحذف) inline-flex أيضاً لتفادي baseline */
.pos-del-form{
  display:inline-flex;            /* مهم */
  align-items:center;             /* توسيط رأسي */
  margin:0; padding:0;            /* إلغاء أي فراغات */
  vertical-align:middle;
}

/* الأزرار الأيقونية المصغّرة */
.org-tree .uf-mini{
  width:26px; height:26px;        /* صغّر/كبّر حسب رغبتك */
  padding:0;
  display:inline-flex;
  align-items:center;
  justify-content:center;
  line-height:1;
  border-radius:10px;
  vertical-align:middle;          /* توحيد السطر */
}
.org-tree .uf-mini i{
  font-size:13px;
  line-height:1;
}
/* محاذاة مجموعة الأزرار على خط واحد */
.actions{ align-items: center; }

/* جعل نموذج الحذف inline-flex لتفادي baseline واختلاف الارتفاع */
.uf-del-form{
  display: inline-flex;
  align-items: center;
  margin: 0; padding: 0;
  vertical-align: middle;
}

/* زر أيقوني صغير موحّد الحجم */
.org-tree .uf-mini{
  width: 26px; height: 26px;      /* غيّرها لـ 24px إذا أردت أصغر */
  padding: 0;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  line-height: 1;
  border-radius: 10px;
  vertical-align: middle;
}
.org-tree .uf-mini i{ font-size: 13px; line-height: 1; }


</style>
