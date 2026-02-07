<style>
:root{
  --brand:#2796cf;           /* Primary */
  --brand-2:#2796cf;         /* (مكرر حسب طلبك) */
  --surface:#cce2f0;         /* Subtle surface */
  --ink:#0e1114;             /* النص الداكن */
  --brand-deep:#1a4d5f;      /* عناوين داكنة */
  --muted:#acacad;           /* نص خافت */
  --white:#ffffff;
}

/* نص عام */
body{ color:var(--ink); }

/* Toolbar موحّد */
.brand-toolbar .brand-title{ color:var(--brand-deep); font-weight:800; letter-spacing:.3px }
.brand-badge{ background:var(--surface); color:var(--brand-deep); font-weight:700; border-radius:8px; padding:.35rem .6rem; }

/* حقول */
.brand-control{
  border:1px solid #e5e7eb; background:var(--white);
  transition:border .15s, box-shadow .15s;
}
.brand-control:focus{
  border-color: var(--brand);
  box-shadow: 0 0 0 3px rgba(39,150,207,.15);
}

/* أزرار */
.btn-brand{ background:var(--brand); color:#fff; border:none; box-shadow:0 1px 0 rgba(0,0,0,.04); }
.btn-brand:hover{ background:#2388ba; color:#fff }
.btn-ghost{ background:transparent; border:1px solid #e5e7eb; color:var(--brand-deep); }
.btn-ghost:hover{ background:var(--surface); border-color:var(--brand); color:var(--brand-deep) }

/* جدول */
.brand-table thead th{ background:var(--surface); color:var(--brand-deep); border-bottom:2px solid var(--brand) }
.brand-table tbody td{ border-color:#eef2f7 }

/* أفاتار */
.brand-avatar{ width:34px;height:34px;border-radius:50%; background:#f5f7fa;color:var(--brand);display:inline-flex;align-items:center;justify-content:center; }
.brand-avatar-lg{ width:48px;height:48px;border-radius:50%; background:#f5f7fa;color:var(--brand);display:inline-flex;align-items:center;justify-content:center;font-size:24px }

/* شبكات */
.info-grid{ display:grid; grid-template-columns: repeat(2, minmax(0,1fr)); gap:10px }
.info-label{ color:var(--muted) }
.info-value{ font-weight:700; color:var(--ink) }
.form-grid{ display:grid; grid-template-columns: repeat(2, minmax(0,1fr)); gap:12px }
@media (max-width: 992px){ .form-grid, .info-grid{ grid-template-columns: 1fr; } }

/* Legend (مطابقة للتقرير الشهري) */
.legend{display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:6px;font-weight:700}
.l-p{background:#e9f7ef;color:#1e7e34}
.l-l{background:#fff4e5;color:#b35c00}
.l-a{background:#fdecea;color:#a71d2a}
.l-v{background:#e8f1ff;color:#0b5ed7}
.l-wk{background:#f1f3f5;color:#6c757d}
.l-r{background:#eee;color:#6c757d}

/* مدخلات البحث */
.brand-input .input-group-text{ background:var(--brand); color:#fff; border-color:var(--brand) }
.brand-input .form-control{ border-color:var(--brand); }
.brand-input .form-control:focus{ box-shadow:none }

/* روابط */
a{ color:var(--brand-deep) }
a:hover{ color:var(--brand) }
</style>