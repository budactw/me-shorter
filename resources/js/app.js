import '../css/app.css';
import "flatpickr/dist/flatpickr.min.css";
import flatpickr from "flatpickr";
import {MandarinTraditional} from "flatpickr/dist/l10n/zh-tw.js"
import QRCodeStyling from "qr-code-styling";

// 將 QRCodeStyling 附加到全域 window 物件
window.QRCodeStyling = QRCodeStyling;

flatpickr("#expired_at", {
  enableTime: false,
  dateFormat: "Y-m-d",
  allowInput: false,
  defaultDate: null,
  minDate: "today",
  maxDate: new Date().setFullYear(new Date().getFullYear() + 1),
  time_24hr: true,
  'locale': MandarinTraditional,
});
