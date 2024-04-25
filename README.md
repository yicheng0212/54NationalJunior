**全國技能競賽青少年組（J17網頁技術）**
===
[競賽試題](https://ws.wda.gov.tw/Download.ashx?u=LzAwMS9VcGxvYWQvMzMxL3JlbGZpbGUvMTAyNTAvMTYxMjc3LzdjNzdmMjU0LTFjMzAtNGFmZS05MDNlLWUwYzY3M2Y2MmY2NC5wZGY%3d&n=NTMtSjE357ay6aCB5oqA6KGTLnBkZg%3d%3d)
---
[競賽解題](https://github.com/yicheng0212/54NationalJunior)
---

**卍O航航與~~廢物的~~Vue小教室oO卐**
===
引入CDN
---
```html=
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
```
一、建立Vue應用程式
---
- 使用mount綁定ID
```html=
<div id="app">
  <p>{{ text }}</p><!-- 將Vue的資料顯示需用大括號 {{}} 綁定文字 -->
</div>

<script>
//當網頁載入完成後執行
document.addEventListener('DOMContentLoaded', function() {
    //設置資料
    Vue.createApp({
        data() {
            return {
                text: 'Hello Vue.js'
            }
        }
    }).mount('#app')  //將id為app的物件掛載
});
</script>
```
---
二、v-model雙向綁定的資料
---
1.v-model : 透過大括號 {{}} 方式綁定文字
```html=
<div id="app">
   <input type="text" v-model="this.message"><!-- input欄位裡面的值為message -->
   <p>{{ message }}</p><!-- 將message的值顯示 -->
</div>
<script>
    Vue.createApp({
          data() {
              return {
                  message: ""//假設沒有初始值這段可以不寫
              }
          }
    }).mount("#app")
</script>
```
---
三、v-bind動態屬性指令
--
- 透過v-bind添加html屬性，類似setAttribute / jQ的attr
- v-bind:的簡寫可以直接省略v-bind寫成:
```html=
<style>
    .myClass {
        color: #f00
    }
</style>


<div id="app">
<!-- <div class="myClass"></div> html -->
<!-- <div v-bind:class="this.className"></div> 原文 -->
    <div :class="this.className"></div><!-- 改變 class -->
    <img :src="imgSrc"><!-- 改變src -->
</div>

<script>
    Vue.createApp({
    	data() {
    	    return {
    	        className: "myClass",
    	        imgSrc: "https://avatars.githubusercontent.com/u/106894066?v=4"
    	    }//JSON格式
    	}
    }).mount("#app")
</script>
```
---
四、v-for迴圈
---
- 動態產生多筆資料於畫面上當括號中有不同筆數的資料
- 類似foreach
```html=
<div id="app">
    <div v-for="(value, key, index) in this.array">
        <p>{{ value }}</p>
        <p>{{ key }}</p>
        <p>{{ index }}</p>
    </div>

	<div v-for="(key, index) in this.array">
	    <p>{{ key }}</p>
	    <p>{{ index }}</p>
	</div>

	<div v-for="key in this.array">
	    <p>{{ index }}</p>
	</div>
</div>

<script>
    Vue.createApp({
        data() {
            return {
                array: [1 => "1", 2 => "2", 3 => "3", 4 => "4", 5 => "5"]
            }
        }
    }).mount('#app')
</script>
```
- value : 回傳的是陣列中的屬性值
- key : 回傳的是陣列中的屬性名稱
- index : 回傳的是陣列中的索引值
---
五、v-if判斷式
---
- 類似if-else判斷式
- 假設為true會直接將該DOM刪掉，而不是隱藏
```html=
<div id="app">
    <div v-if="this.judgmental">true</div><!-- 假設judgmental為true才會顯示 -->
    <div v-else >false</div><!-- 找到最近的v-if做判斷 -->
</div>

<script>
    Vue.createApp({
          data() {
              return {
                  judgmental: true
              }
          }
    }).mount("#app")
</script>
```
---
六、v-show改變css的display
---
- 類似if判斷式，但是沒有else
- 和if不一樣，if是直接將DOM刪掉，show則是隱藏
```html=
<div id="app">
    <div v-if="this.isShow">true</div><!-- 假設isShow為true則會將 -->
</div>

<script>
    Vue.createApp({
          data() {
              return {
                  isShow: true
              }
          }
    }).mount("#app")
</script>
```
---
七、v-on操作頁面行為
---
- v-on: 的簡寫可以直接省略v-on寫成@
- v-on後面是接要執行的事件，事件後面寫="函式名稱"
```html=
<div id="app">
    <p>Count is: {{ count }}</p>
    <button @click="count++">Click me!</button>
</div>
<script>
    Vue.createApp({
        data(){
            return {
                count: 0
            }
        }
    }).mount("#app")
</script>
```
---
**Wwv以下為option Api使用方式vwW**
===
八、methods方法
---
- Vue的函式都要寫在methods
- ++***不要忘記加s***++
- ++***不要忘記加s***++
- ++***不要忘記加s***++
```html=
<div id="app">
    <p>Count is: {{ count }}</p>
    <button @click="clickMe">Click me!</button>
</div>

<script>
    Vue.createApp({
        data(){
            return {
                count: 0
            }
        },
        //函式監聽器要寫在這裡面
        methods: {
            clickMe() {
                this.count++
            }
        }
    }).mount("#app")
</script>
```
---
九、生命週期的Hook function
---
![生命週期](https://hackmd.io/_uploads/Skk373QWA.png)
---
十、生命週期mounted
---
- mounted會在Vue最一開始時直接執行
- 裡面可以放要執行的程式碼或函式
```html=
<div id="app">
    <p>Count is: {{ count }}</p><!-- 這時的count並不是0，而是1 -->
</div>
<script>
    Vue.createApp({
        data(){
            return {
                count: 0
            }
        },
        mounted() {
            this.count = 1
        }
    }).mount("#app")
</script>
```
---