package api

import (
	"github.com/gin-gonic/gin"
	"github.com/gorilla/websocket"
	"github.com/sirupsen/logrus"
	"github.com/soheilhy/cmux"
	"io"
	"net"
	"net/http"
)

var upgrader = websocket.Upgrader{}

func Server (l net.Listener) {
	r := gin.Default()

	// ws echo
	r.Any("/ws", func(c *gin.Context) {
		r := c.Request
		w := c.Writer
		conn, err := upgrader.Upgrade(w, r, nil)
		if err != nil {
			logrus.Error("err = %s\n", err)
			return
		}

		defer func() {
			// 发送websocket结束包
			conn.WriteMessage(websocket.CloseMessage,
				websocket.FormatCloseMessage(websocket.CloseNormalClosure, ""))
			// 真正关闭conn
			_ = conn.Close()
		}()
		// 读取一个包
		mt, d, err := conn.ReadMessage()
		if err != nil {
			logrus.Error("read fail = %v\n", err)
			return
		}

		// 写入一个包
		err = conn.WriteMessage(mt, d)
		if err != nil {
			logrus.Error("write fail = %v\n", err)
			return
		}
	})

	// api echo
	r.GET("/api", func(c *gin.Context) {
		_, _ = io.Copy(c.Writer, c.Request.Body)
	})

	s := &http.Server{
		Handler: r,
	}

	if err := s.Serve(l); err != cmux.ErrListenerClosed {
		panic(err)
	}
}