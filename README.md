# EMSTracking-Thailandpos

เช็คสถานะพัสดุ EMS ไทยไปรษณีย์ (Thailand Post) ด้วย PHP

## Response

### Identifiers
ข้อมูลระบุตัวตนของพัสดุ ใช้ query/tracking หลัก
```json
{
  "mailingNo": "เลขพัสดุ เช่น ED435000316TH",
  "logID": "ID เฉพาะของ log entry นี้",
  "trnstnNbr": "เลขอ้างอิงธุรกรรมขนส่งระหว่างสาขา",
  "prefix": "ตัวอักษรนำหน้า เช่น ED",
  "suffix": "ตัวอักษรต่อท้าย เช่น TH"
}
```

### Timestamp
```json
{
  "createDate": "เวลาที่ event เกิดขึ้นจริง (epoch ms)"
}
```

### Status & Route
สถานะพัสดุ + เส้นทางขนส่ง
```json
{
  "statusID": "รหัสสถานะ",
  "statusGroup": "1=รับฝาก, 2=ระหว่างขนส่ง, 3=นำจ่ายแล้ว",
  "statusName": "ชื่อสถานะ (EN/TH/CN)",
  "statusDetail": "รายละเอียด บอกต้นทาง-ปลายทาง",
  "outletName": "ชื่อสาขา/ศูนย์ที่บันทึก event นี้",
  "recipientName": "เส้นทาง ต้นทาง : ปลายทาง ของรอบขนส่งนี้",
  "latlng": "พิกัด GPS ใช้ปักหมุดแผนที่"
}
```

### Package Detail
```json
{
  "weight": "น้ำหนัก (กก.)",
  "mailPostCode": "รหัสไปรษณีย์ปลายทาง"
}
```

### Recipient
```json
{
  "addressName": "ชื่อผู้รับ",
  "addressTelephone": "เบอร์ผู้รับ",
  "addressPostCode": "รหัสไปรษณีย์ผู้รับ"
}
```

### Proof of Delivery
```json
{
  "signatureUrl": "ลิงก์รูปลายเซ็นผู้รับ",
  "podImgUrl": "ลิงก์รูปหลักฐานส่ง",
  "receiveDate": "วันที่ได้รับจริง"
}
```

### COD
```json
{
  "flagCOD": "1 = เป็นพัสดุ COD",
  "getMoneyAmount": "ยอดเงินที่เก็บได้จริง"
}
```

## Example

Timeline จริงของพัสดุ `ED435000316TH`:

| เวลา | สถานะ | สถานที่ |
|---|---|---|
| 2026-07-16 18:05 | รับฝาก | นครนายก |
| 2026-07-16 18:22 | ออกจากที่ทำการ | นครนายก → กบินทร์บุรี |
| 2026-07-16 20:05 | เข้าศูนย์คัดแยก | กบินทร์บุรี |
| 2026-07-16 21:39 | ออกจากศูนย์คัดแยก | กบินทร์บุรี → นครราชสีมา |
| 2026-07-17 01:01 | เข้าศูนย์คัดแยก | นครราชสีมา |
