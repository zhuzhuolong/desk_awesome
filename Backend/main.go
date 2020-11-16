package main

import (
	"desk_awesome/api"
	"desk_awesome/utils"
	"github.com/soheilhy/cmux"
	"log"
	"net"
	"os"
	"strings"
)


func init () {
	utils.CfgInit()
}

func main () {
	ln, err := net.Listen("tcp", os.Getenv("PORT"))
	if err != nil {
		log.Fatal(err)
	}
	m := cmux.New(ln)

	// Match connections in order:
	// First grpc, then HTTP, and otherwise Go RPC/TCP.
	//grpcL := m.Match(cmux.HTTP2HeaderField("content-type", "application/grpc"))
	httpL := m.Match(cmux.HTTP1Fast())
	// Create your protocol servers.
	//grpcS := grpc.NewServer()
	//grpchello.RegisterGreeterServer(grpcS, &server{})
	go api.Server(httpL)
	if err := m.Serve(); !strings.Contains(err.Error(), "use of closed network connection") {
		panic(err)
	}
	//r := gin.Default()
	//r.GET("/ping", func(c *gin.Context) {
	//	c.JSON(200, gin.H{
	//		"message": "pong",
	//	})
	//})
	//r.Run()
}