全國技能競賽青少年組（J17網頁技術）
===
[競賽試題](https://ws.wda.gov.tw/Download.ashx?u=LzAwMS9VcGxvYWQvMzMxL3JlbGZpbGUvMTAyNTAvMTYxMjc3LzdjNzdmMjU0LTFjMzAtNGFmZS05MDNlLWUwYzY3M2Y2MmY2NC5wZGY%3d&n=NTMtSjE357ay6aCB5oqA6KGTLnBkZg%3d%3d)
---
---
卍Oo航航與廢物的Vue小教室oO卐
===
引入CDN
---
```html
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
```
一、建立Vue應用程式
---
- 使用mount綁定ID
```html=
<div id="app">
  <p>{{ this.text }}</p><!-- 將 vue 的資料顯示需用大括號 {{}} 綁定文字 -->
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
    }).mount('#app')  //將 id 為 app 的物件掛載
});
</script>
```
---
二、v-model 雙向綁定的資料
---
1.v-model : 透過大括號 {{}} 方式綁定文字
```html=
<div id="app">
   <input type="text" v-model="this.message"><!-- input 欄位裡面的值為 message -->
   <p>{{ this.message }}</p><!-- 將 message 的值顯示 -->
</div>
<script>
    Vue.createApp({
          data() {
              return {
                  message: ""//假設沒有初始值可以不寫
              }
          }
  }).mount("#app")
</script>
```
---
三、v-bind 動態屬性指令
--
- 透過 v-bind 添加 html 屬性，類似 setAttribute / jQ的attr
- v-bind: 的簡寫可以直接省略 v-bind 寫成 :
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
    <img :src="imgSrc"><!-- 改變 src -->
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
四、v-for 迴圈
---
- 動態產生多筆資料於畫面上當括號中有不同筆數的資料
- 類似 foreach
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
                array: [1, 2, 3, 4, 5]
            }
        }
    }).mount('#app')
</script>
```
- value : 回傳的是陣列中的屬性值
- key : 回傳的是陣列中的屬性名稱
- index : 回傳的是陣列中的索引值
---
五、v-if 判斷式
---
- 類似 if-else 判斷式
```html=
<div id="app">
    <div v-if="this.judgmental">true</div><!-- 假設 judgmental 為 true 才會顯示 -->
    <div v-else >false</div><!-- 找到最近的 v-if 做判斷 -->
</div>

<script>
    Vue.createApp({
          data() {
              return {
                  judgmental: true
              }
          },
  }).mount("#app")
</script>
```
---
六、使用 v-on 來操作頁面行為
---
- v-on: 的簡寫可以直接省略 v-on 寫成 @
- v-on後面是接要執行的事件，事件後面寫="函式名稱"
```html=
	<div id="app">
        <button @click="clickMe">Click me!</button>
	</div>
	
	<script>
	    Vue.createApp({
			data() {
			    return {
			        
			    }
			}
            const clickMe = () => {
                $("button").text("You clicked me!")
            }//函式事件
            return {
                
            }
	  }).mount("#app")
	</script>
```
