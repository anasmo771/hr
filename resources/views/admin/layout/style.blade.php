 <!-- [Favicon] icon -->
 <link rel="icon" href="{{asset('assets/New/images/logggo.png')}}" type="image/x-icon">
 <!-- [Font] Family -->
<link rel="stylesheet" href="{{asset('assets/New/fonts/inter/inter.css')}}" id="main-font-link" />
<!-- [Tabler Icons] https://tablericons.com -->
<link rel="stylesheet" href="{{asset('assets/New/fonts/tabler-icons.min.css')}}" />
<!-- [Feather Icons] https://feathericons.com -->
<link rel="stylesheet" href="{{asset('assets/New/fonts/feather.css')}}" />
<!-- [Font Awesome Icons] https://fontawesome.com/icons -->
<link rel="stylesheet" href="{{asset('assets/New/fonts/fontawesome.css')}}" />
<!-- [Material Icons] https://fonts.google.com/icons -->
<link rel="stylesheet" href="{{asset('assets/New/fonts/material.css')}}" />
<!-- [Template CSS Files] -->
<link rel="stylesheet" href="{{asset('assets/New/css/style.css')}}" id="main-style-link" />
<link rel="stylesheet" href="{{asset('assets/New/css/style-preset.css')}}" />
<style>
  :root{
    --card-bg:#fff;
    --card-br:#eef2f7;
    --soft-shadow:0 8px 24px rgba(0,0,0,.06);
    --radius:16px;
  }

  .glass-card{
    background: var(--card-bg);
    border:1px solid var(--card-br);
    border-radius: var(--radius);
    box-shadow: var(--soft-shadow);
    padding: 16px;
  }

  .card-head{
    display:flex; align-items:center; justify-content:space-between;
    gap:10px; margin-bottom:10px;
  }

  .metric-card{
    background: var(--card-bg);
    border:1px solid var(--card-br);
    border-radius: var(--radius);
    box-shadow: var(--soft-shadow);
    padding:16px;
    display:flex; align-items:center; justify-content:space-between; gap:12px;
    min-height:110px;
  }
  .metric-title{ font-size:.9rem; color:#6b7280; }
  .metric-value{ font-size:1.6rem; font-weight:700; line-height:1; margin-top:6px; }
  .metric-sub{ margin-top:6px; font-size:.8rem; }
  .metric-delta{ font-weight:600; margin-inline-end:6px; }
  .metric-badge{
    width:48px;height:48px;border-radius:50%;
    display:flex;align-items:center;justify-content:center;
    background:#f3f4f6; color:#111827; font-weight:700;
  }

  .avatar-xs{ width:34px; height:34px; object-fit:cover; }

  /* تحسينات صغيرة للبحث والنطاق */
  .dash-search input{ min-width:240px; }
  .dash-range{ min-width:140px; }

  /* اتجاه RTL جاهز تلقائياً من الـ <html dir="rtl"> لديك */
</style>
<style>
  /* اجعل البطاقة رابطًا أنيقًا */
  .metric-link { text-decoration: none; color: inherit; display:block; }
  .metric-card { transition: transform .15s ease, box-shadow .15s ease, border-color .15s ease; cursor: pointer; }
  .metric-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 28px rgba(0,0,0,.08);
    border-color: #dbe2ea;
  }
  .metric-badge {
    position: relative; overflow: hidden;
  }
  .metric-badge .go-chip{
    position:absolute; inset-inline-end:-6px; inset-block-end:-6px;
    font-size:14px; opacity:.0; transform: translate(-6px,-6px);
    transition: opacity .15s ease, transform .15s ease;
  }
  .metric-card:hover .go-chip{ opacity:.65; transform: translate(-2px,-2px); }
</style>
